<script lang="ts" setup>
import {Head, InertiaForm, router, useForm} from '@inertiajs/vue3';
import {useToast} from 'primevue/usetoast';
import {useDark} from '@vueuse/core';

/// <reference path="../../types/generated.d.ts" />
const form: InertiaForm<App.Data.InstrumentData> = useForm({
    id: 0,
    user_id: 0,
    name: '',
    symbol: '',
    isin: '',
    last_price: null,
});
const toast = useToast();
const isDark = useDark();

const submit = () => {
    form.post(route('instruments.store'), {
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
    <div class="m-4 p-4 bg-surface-50 dark:bg-surface-800 shadow sm:rounded-lg max-w-6xl">
        <Panel :pt="{ header: 'p-2 px-4' }">
            <template #header>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-medium uppercase">Add New Instrument</h2>
                </div>
            </template>
            <div class="m-0">
                <form class="mt-1 space-y-2" @submit.prevent="submit">
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
                            @click="router.visit(route('instruments.index'), { preserveState: true })"/>
                    </div>
                </form>
            </div>
        </Panel>
    </div>
</template>
