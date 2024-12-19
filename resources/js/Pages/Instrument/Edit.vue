<script setup lang="ts">
import {Head, InertiaForm, router, useForm} from '@inertiajs/vue3';
import {useToast} from 'primevue/usetoast';
import {route} from 'ziggy-js';
import {useDark} from '@vueuse/core';

/// <reference path="../../types/generated.d.ts" />
type InstrumentDataProp = {
    instrument: App.Data.InstrumentData;
    previous: { id: string } | null;
    next: { id: string } | null;
};
const props = defineProps<InstrumentDataProp>();
const formData = Object.assign({}, props.instrument);

const form: InertiaForm<App.Data.InstrumentData> = useForm<App.Data.InstrumentData>(`EditInstrument:${props.instrument.id}`, formData);
const toast = useToast();
const isDark = useDark();

const submit = () => {
    form.patch(route('instruments.update', {instrument: form.id!}), {
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
    router.get(route('instruments.index'), route().queryParams, {preserveState: true});
};
</script>
<template>
    <FloatLabel></FloatLabel>
    <Head title="Edit Instrument"/>
    <div class="m-4 p-4 bg-surface-50 dark:bg-surface-800 shadow sm:rounded-lg max-w-6xl">
        <Panel :pt="{ header: 'p-2 px-4' }">
            <template #header>
                <div class="flex items-center justify-between w-full gap-2 px-2">
                    <h2 class="text-lg font-medium uppercase">Edit Instrument</h2>
                    <ButtonGroup>
                        <Button
                            icon="pi pi-caret-left"
                            size="small"
                            severity="secondary"
                            @click="router.get(route('instruments.edit', previous?.id), undefined, { replace: true })"
                            :disabled="!previous"/>
                        <Button
                            icon="pi pi-caret-right"
                            size="small"
                            severity="secondary"
                            @click="router.get(route('instruments.edit', next?.id), undefined, { replace: true })"
                            :disabled="!next"/>
                    </ButtonGroup>
                </div>
            </template>
            <div class="m-0">
                <form @submit.prevent="submit" class="mt-1 space-y-2 md:space-y-6">
                    <div class="flex flex-col gap-1">
                        <label class="labelclass" for="name">Name</label>
                        <InputText
                            id="name"
                            v-model="form.name"
                            :class="form.errors.name ? '!border-red-300' : ''"/>
                        <FormError :message="form.errors.name"/>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="symbol">Symbol</label>
                            <InputText
                                id="symbol"
                                v-model="form.symbol"
                                :class="form.errors.symbol ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.symbol"/>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="isin">ISIN</label>
                            <InputText
                                id="isin"
                                v-model="form.isin"
                                :class="form.errors.isin ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.isin"/>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="labelclass" for="last_price">Last Price</label>
                        <InputNumber
                            inputId="last_price"
                            v-model="form.last_price"
                            :min="0" :maxFractionDigits="2"
                            :useGrouping="false" :class="form.errors.last_price ? '!border-red-300' : ''"/>
                        <FormError :message="form.errors.last_price"/>
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
