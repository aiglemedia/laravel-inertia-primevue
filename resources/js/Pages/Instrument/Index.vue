<script setup lang="ts">
import {ref} from 'vue';
import {Head, router} from '@inertiajs/vue3';
import {route} from 'ziggy-js';
import {useConfirm} from 'primevue/useconfirm';

/// <reference path="../../types/generated.d.ts" />
type InstrumentDataProp = {
    instruments: {
        data: Array<App.Data.InstrumentData>;
        links: Array<Object>;
        meta: { [key: string]: any };
    };
};

const props = defineProps<InstrumentDataProp>();
const confirm = useConfirm();

const showTemplate = (id: any, name: any) => {
    confirm.require({
        group: 'headless',
        header: 'Are you sure?',
        message: `Deleting the record will remove record from database for instrument ${name}`,
        accept: () => router.delete(route('instruments.destroy', id), {data: route().queryParams}),
    });
};

const dt = ref();
const loading = ref(false);
const loadLazyData = (event?: any) => {
    loading.value = true;
    router.get(route('instruments.index', {page: ++event.originalEvent.page}), undefined, {
        preserveState: true,
        onFinish: () => {
            loading.value = false;
        },
    });
};
const onPage = (event?: any) => {
    loadLazyData(event);
};
</script>
<style lang="postcss"></style>
<template>
    <Head title="Instruments"/>
    <div class="m-4 p-4 bg-surface-50 dark:bg-surface-800 shadow sm:rounded-lg max-w-max">
        <DataTable
            ref="dt"
            :value="props.instruments.data"
            dataKey="id"
            :tableStyle="{ 'min-width': '50rem' }"
            size="small"
            showGridlines
            lazy
            :paginator="props.instruments.meta.total > 10"
            :first="props.instruments.meta.from"
            :rows="10"
            :total-records="props.instruments.meta.total"
            :loading="loading"
            @page="onPage($event)">
            <template #header>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex justify-between items-center gap-2 grow">
                        <h2 class="text-lg font-medium whitespace-nowrap">Instrument List</h2>
                        <div class="flex gap-1">
                            <Button
                                label="New"
                                severity="secondary"
                                icon="pi pi-plus"
                                size="small"
                                @click="router.visit(route('instruments.create'), { preserveState: true })"/>
                            <!--                            <a
                                                            v-if="props.instruments.data.length > 0"
                                                            :href="route('export.instruments', { type: 'excel' })"
                                                            class="relative items-center inline-flex text-center align-bottom justify-center text-sm font-bold py-2 px-3 rounded-md bg-gray-500 text-white border border-gray-500 focus:outline-none focus:outline-offset-0 focus:ring hover:bg-gray-600 hover:border-gray-600 dark:hover:border-gray-300 focus:ring-gray-400/50 dark:focus:ring-gray-300/50 transition duration-200 ease-in-out cursor-pointer overflow-hidden select-none"
                                                            ><i class="pi pi-file-excel mr-2"></i>Export</a
                                                        >-->
                        </div>
                    </div>
                </div>
            </template>
            <Column header="Sn">
                <template #body="{ index }">
                    <div class="flex items-center justify-center">
                        {{ (props.instruments.meta.current_page - 1) * props.instruments.meta.per_page + index + 1 }}
                    </div>
                </template>
            </Column>
            <Column header="Name" field="name"/>
            <Column header="Symbol" field="symbol"/>
            <Column header="ISIN" field="isin"/>
            <Column header="Last Price" field="last_price"/>
            <Column field="id">
                <template #body="{ data }">
                    <div class="flex flex-nowrap gap-1">
                        <Button
                            label="Trades"
                            severity="secondary"
                            size="small"
                            @click="router.get(route('trades.index', {instrument_id: data.id}), route().params)"
                            :pt="{ root: '!py-1' }"/>
                        <Button
                            label="Edit"
                            icon="pi pi-pen-to-square"
                            severity="secondary"
                            size="small"
                            @click="router.get(route('instruments.edit', data.id), route().params)"
                            :pt="{ root: '!py-1' }"/>
                        <Button
                            label="Delete"
                            icon="pi pi-trash"
                            severity="danger"
                            size="small"
                            @click="showTemplate(data.id, data.name)"
                            :pt="{ root: '!py-1' }"/>
                    </div>
                </template>
            </Column>
            <template #empty>
                <div class="flex justify-center items-center font-bold text-xl">No Records found in database.</div>
            </template>
        </DataTable>
    </div>
</template>
