<script setup lang="ts">
import {Head, InertiaForm, router, useForm} from '@inertiajs/vue3';
import {useToast} from 'primevue/usetoast';
import {route} from 'ziggy-js';
import {useDark} from '@vueuse/core';

/// <reference path="../../types/generated.d.ts" />
type TradeDataProp = {
    trade: App.Data.TradeData;
    previous: { id: string } | null;
    next: { id: string } | null;
};
const props = defineProps<TradeDataProp>();
const formData = Object.assign({}, props.trade);

const form: InertiaForm<App.Data.TradeData> = useForm<App.Data.TradeData>(`EditTrade:${props.trade.id}`, formData);
const toast = useToast();
const isDark = useDark();

const submit = () => {
    form.patch(route('trades.update', {trade: form.id!}), {
        onSuccess: () => {
            console.log('success');
        },
        preserveScroll: true,
        onError: () => {
            console.log(form.errors);
            toast.add({severity: 'error', summary: 'Error', detail: 'Please correct the errors.', life: 5000});
        },
    });
};

const goback = () => {
    //window.history.back();
    router.get(route('trades.index'), route().queryParams, {preserveState: true});
};
</script>
<template>
    <FloatLabel></FloatLabel>
    <Head title="Edit Trade"/>
    <div class="m-4 p-4 bg-surface-50 dark:bg-surface-800 shadow sm:rounded-lg max-w-6xl">
        <Panel :pt="{ header: 'p-2 px-4' }">
            <template #header>
                <div class="flex items-center justify-between w-full gap-2 px-2">
                    <h2 class="text-lg font-medium uppercase">Edit Trade</h2>
                    <ButtonGroup>
                        <Button
                            icon="pi pi-caret-left"
                            size="small"
                            severity="secondary"
                            @click="router.get(route('trades.edit', previous?.id), route().queryParams, { replace: true })"
                            :disabled="!previous"/>
                        <Button
                            icon="pi pi-caret-right"
                            size="small"
                            severity="secondary"
                            @click="router.get(route('trades.edit', next?.id), route().queryParams, { replace: true })"
                            :disabled="!next"/>
                    </ButtonGroup>
                </div>
            </template>
            <div class="m-0">
                <form @submit.prevent="submit" class="mt-1 space-y-2 md:space-y-6">
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
                            label="Update"
                            type="submit"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing || !form.isDirty"
                            size="small"/>
                        <Button label="Back" severity="secondary" size="small" @click="goback"/>
                    </div>
                </form>
            </div>
        </Panel>
    </div>
</template>
