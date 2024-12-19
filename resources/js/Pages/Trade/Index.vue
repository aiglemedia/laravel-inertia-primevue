<script setup lang="ts">
import {computed, ref} from 'vue';
import {Head, router} from '@inertiajs/vue3';
import {route} from 'ziggy-js';
import {useConfirm} from 'primevue/useconfirm';
import {SelectChangeEvent} from 'primevue/select';
import {assignIn} from 'lodash';
import {DateTime} from "luxon";
import {useDialog} from 'primevue/usedialog';
import TradeForm from './TradeForm.vue';

/// <reference path="../../types/generated.d.ts" />
type InstrumentList = Pick<App.Data.InstrumentData, 'id' | 'name'>;
type TradeDataProp = {
    instrumentList: InstrumentList[];
    trades: Array<App.Data.TradeData>;
};

const props = defineProps<TradeDataProp>();
const confirm = useConfirm();
const instrument_id = Number(route().params.instrument_id);
const loading = ref(false);
const selectedInstrument = ref(instrument_id);
const oldSelected = ref(instrument_id);
const dt = ref();
const dialog = useDialog();

function formDialog(params: any) {
    dialog.open(TradeForm, {
        props: {
            style: {
                width: '95vw',
                maxWidth: '36rem',
            },
            modal: true,
            contentClass: 'p-2',
            showHeader: false,
            position: 'top',
        },
        onClose: (options: any) => {
            const data = options.data;
            if (data) {
                //toast.add({ severity: 'info', detail: data, life: 3000 });
            }
        },
        data: params,
    });
}

const addTrade = () => {
    formDialog({
        new: true,
        instrument_id: selectedInstrument.value
    })
}

const editTrade = (data: any) => {
    //router.get(route('trades.edit', data.id), route().params);
    formDialog({
        new: false,
        trade: data
    })
}

const showTemplate = (id: any, param: any) => {
    confirm.require({
        group: 'headless',
        header: 'Are you sure?',
        message: `Deleting the record will remove record from database`,
        accept: () => router.delete(route('trades.destroy', id), {data: route().queryParams}),
    });
};

const loadLazyData = (params: Object, fresh: boolean = true) => {
    loading.value = true;
    let rParams = fresh ? params : assignIn(route().params, params);
    router.get(route('trades.index', {...rParams}), undefined, {
        preserveState: true,
        onFinish: () => {
            loading.value = false;
        },
    });
};
/*const onPage = (event?: any) => {
    loadLazyData({page: ++event.originalEvent.page}, false);
};*/

const onInstrumentChange = (event?: SelectChangeEvent) => {
    if (oldSelected.value == event?.value) return;
    oldSelected.value = event?.value;
    loadLazyData({instrument_id: event?.value}, false);
};

const totalQuantity = computed(() => {
    return props.trades
        // .filter(({ selected }) => selected === true)
        .reduce((sum, trade) => {
            return isNaN(Number(trade.quantity)) ? sum : sum + Number(trade.quantity);
        }, 0);
});

const totalAmount = computed(() => {
    return props.trades
        // .filter(({ selected }) => selected === true)
        .reduce((sum, trade) => {
            return isNaN(Number(trade.price)) || isNaN(Number(trade.quantity))
                ? sum
                : sum + Number(trade.price) * Number(trade.quantity);
        }, 0)
        .toFixed(2) as unknown as number;
});

const avgPrice = computed(() =>
    isNaN(
        (totalAmount.value / totalQuantity.value).toFixed(2) as unknown as number
    )
        ? (0).toFixed(2)
        : (totalAmount.value / totalQuantity.value).toFixed(2)
);

function formatDate(date: string | null) {
    return date ? DateTime.fromSQL(date).toFormat('dd-MM-yyyy') : '';
}
</script>
<style lang="postcss"></style>
<template>
    <Head title="Instruments"/>
    <div class="flex flex-col gap-2 p-3 lg:p-4 mt-1 max-w-max">
        <div class="flex flex-col" v-if="props.instrumentList?.length">
            <FloatLabel class="w-full" variant="on">
                <Select
                    v-model="selectedInstrument"
                    inputId="selectedInstrument"
                    :options="props.instrumentList"
                    optionLabel="name"
                    optionValue="id"
                    class="w-full"
                    size="small"
                    @change="onInstrumentChange"/>
                <label for="selectedEvent">Select Instrument</label>
            </FloatLabel>
        </div>
        <div class="p-4 bg-surface-50 dark:bg-surface-800 shadow sm:rounded-lg max-w-max">
            <DataTable
                ref="dt"
                :value="props.trades"
                dataKey="id"
                :tableStyle="{ 'min-width': '50rem' }"
                size="small"
                showGridlines
                paginator
                :alwaysShowPaginator="false"
                :rows="8"
                :loading="loading">
                <template #header>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex justify-between items-center gap-2 grow">
                            <h2 class="text-lg font-medium whitespace-nowrap">Instrument Trades</h2>
                            <div class="flex gap-1">
                                <Button
                                    label="Add"
                                    severity="secondary"
                                    icon="pi pi-plus"
                                    size="small"
                                    @click="addTrade"/>
                                <!--                            <a
                                                                v-if="props.trades.data.length > 0"
                                                                :href="route('export.trades', { type: 'excel' })"
                                                                class="relative items-center inline-flex text-center align-bottom justify-center text-sm font-bold py-2 px-3 rounded-md bg-gray-500 text-white border border-gray-500 focus:outline-none focus:outline-offset-0 focus:ring hover:bg-gray-600 hover:border-gray-600 dark:hover:border-gray-300 focus:ring-gray-400/50 dark:focus:ring-gray-300/50 transition duration-200 ease-in-out cursor-pointer overflow-hidden select-none"
                                                                ><i class="pi pi-file-excel mr-2"></i>Export</a
                                                            >-->
                            </div>
                        </div>
                    </div>
                </template>
                <Column header="Purchase Date" field="purchase_date">
                    <template #body="{ data }">
                        {{ formatDate(data.purchase_date) }}
                    </template>
                </Column>
                <Column header="Quantity" field="quantity"/>
                <Column header="Price" field="price"/>
                <Column field="id">
                    <template #body="{ data }">
                        <div class="flex flex-nowrap gap-1">
                            <Button
                                label="Edit"
                                icon="pi pi-pen-to-square"
                                severity="secondary"
                                size="small"
                                @click="editTrade(data)"
                                :pt="{ root: '!py-1' }"/>
                            <Button
                                label="Delete"
                                icon="pi pi-trash"
                                severity="danger"
                                size="small"
                                @click="showTemplate(data.id, data.purchase_date)"
                                :pt="{ root: '!py-1' }"/>
                        </div>
                    </template>
                </Column>
                <template #empty>
                    <div class="flex justify-center items-center font-bold text-xl">No Records found in database.</div>
                </template>
                <template #footer> Total Qty {{ totalQuantity }}. Average Price {{ avgPrice }}.</template>
            </DataTable>
            <!--            <DataTable
                            ref="dt"
                            :value="props.trades.data"
                            dataKey="id"
                            :tableStyle="{ 'min-width': '50rem' }"
                            size="small"
                            showGridlines
                            lazy
                            :paginator="props.trades.meta.total > 10"
                            :first="props.trades.meta.from"
                            :rows="10"
                            :total-records="props.trades.meta.total"
                            :loading="loading"
                            @page="onPage($event)">
                            <template #header>
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex justify-between items-center gap-2 grow">
                                        <h2 class="text-lg font-medium whitespace-nowrap">Instrument Trades</h2>
                                        <div class="flex gap-1">
                                            <Button
                                                label="Add"
                                                severity="secondary"
                                                icon="pi pi-plus"
                                                size="small"
                                                @click="addTrade"/>
                                            &lt;!&ndash;                            <a
                                                                            v-if="props.trades.data.length > 0"
                                                                            :href="route('export.trades', { type: 'excel' })"
                                                                            class="relative items-center inline-flex text-center align-bottom justify-center text-sm font-bold py-2 px-3 rounded-md bg-gray-500 text-white border border-gray-500 focus:outline-none focus:outline-offset-0 focus:ring hover:bg-gray-600 hover:border-gray-600 dark:hover:border-gray-300 focus:ring-gray-400/50 dark:focus:ring-gray-300/50 transition duration-200 ease-in-out cursor-pointer overflow-hidden select-none"
                                                                            ><i class="pi pi-file-excel mr-2"></i>Export</a
                                                                        >&ndash;&gt;
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <Column header="Purchase Date" field="purchase_date">
                                <template #body="{ data }">
                                    {{ DateTime.fromSQL(data.purchase_date).toFormat('dd-MM-yyyy') }}
                                </template>
                            </Column>
                            <Column header="Quantity" field="quantity"/>
                            <Column header="Price" field="price"/>
                            <Column field="id">
                                <template #body="{ data }">
                                    <div class="flex flex-nowrap gap-1">
                                        <Button
                                            label="Edit"
                                            icon="pi pi-pen-to-square"
                                            severity="secondary"
                                            size="small"
                                            @click="editTrade(data)"
                                            :pt="{ root: '!py-1' }"/>
                                        <Button
                                            label="Delete"
                                            icon="pi pi-trash"
                                            severity="danger"
                                            size="small"
                                            @click="showTemplate(data.id, data.purchase_date)"
                                            :pt="{ root: '!py-1' }"/>
                                    </div>
                                </template>
                            </Column>
                            <template #empty>
                                <div class="flex justify-center items-center font-bold text-xl">No Records found in database.</div>
                            </template>
                        </DataTable>-->
        </div>
    </div>

</template>
