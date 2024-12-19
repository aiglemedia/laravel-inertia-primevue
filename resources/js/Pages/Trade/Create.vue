<script lang="ts" setup>
import {Head, InertiaForm, router, useForm} from '@inertiajs/vue3';
import {useToast} from 'primevue/usetoast';
import {useDark} from '@vueuse/core';

/// <reference path="../../types/generated.d.ts" />
type TradeCreateProp = {
    instrument_id: number;
};
const props = defineProps<TradeCreateProp>();
const form: InertiaForm<App.Data.TradeData> = useForm({
    id: 0,
    instrument_id: 0,
    purchase_date: null,
    quantity: 0,
    price: 0,
});
const toast = useToast();
const isDark = useDark();

const submit = () => {
    form.post(route('trades.store'), {
        onSuccess: (res) => {
            if (!res.props.flash.error)
                form.reset();
        },
        preserveScroll: true,
        onError: () => {
            console.log(form.errors);
            toast.add({severity: 'error', summary: 'Error', detail: 'Please correct the errors.', life: 5000});
        },
    });
};
</script>
<template>
    <Head title="Add Instrument"/>
    <div class="m-4 p-4 bg-surface-50 dark:bg-surface-800 shadow sm:rounded-lg max-w-xl">
        <Panel :pt="{ header: 'p-2 px-4' }">
            <template #header>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-medium uppercase">Add Trade</h2>
                </div>
            </template>
            <div class="m-0">
                <form class="mt-1 space-y-2" @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="dp-input-purchase_date">Purchase (dd-mm-yyyy)</label>
                            <VueDatePicker
                                v-model="form.purchase_date"
                                :enable-time-picker="false"
                                format="dd-MM-yyyy"
                                model-type="yyyy-MM-dd"
                                prevent-min-max-navigation
                                uid="purchase_date"
                                :dark="isDark"
                                no-today
                                text-input
                                auto-apply
                                :ui="{
                                    input: form.errors.purchase_date ? 'datetimeinput datetimeinputerror' : 'datetimeinput',
                                }"/>
                            <FormError :message="form.errors.purchase_date"/>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="quantity">Qty</label>
                            <InputNumber
                                inputId="quantity"
                                v-model="form.quantity"
                                :min="0" size="small"
                                :useGrouping="false" :class="form.errors.quantity ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.quantity"/>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="price">Price</label>
                            <InputNumber
                                inputId="price"
                                v-model="form.price"
                                :min="0" :maxFractionDigits="2" size="small"
                                :useGrouping="false" :class="form.errors.price ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.price"/>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <Button
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            label="Submit"
                            size="small"
                            type="submit"/>
                        <Button
                            :disabled="form.processing || !form.isDirty"
                            label="Reset"
                            size="small"
                            type="reset"
                            @click="
                                form.reset();
                                form.clearErrors();
                            "/>
                        <Button
                            label="Cancel"
                            severity="secondary"
                            size="small"
                            @click="router.visit(route('trades.index'), { preserveState: true })"/>
                    </div>
                </form>
            </div>
        </Panel>
    </div>
</template>
