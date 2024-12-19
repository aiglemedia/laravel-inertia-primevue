<script setup lang="ts">
import {Head, InertiaForm, router, useForm} from '@inertiajs/vue3';
import {useToast} from 'primevue/usetoast';
import {route} from 'ziggy-js';
import {useDark} from '@vueuse/core';

/// <reference path="../../types/generated.d.ts" />
type CustomerDataProp = {
    customer: App.Data.CustomerData;
    previous: { id: string } | null;
    next: { id: string } | null;
    invoiceSeries: Array<App.Data.InvoiceSeriesData>;
};
const props = defineProps<CustomerDataProp>();
const formData = Object.assign({}, props.customer);

const form: InertiaForm<App.Data.CustomerData> = useForm<App.Data.CustomerData>(`EditCustomer:${props.customer.id}`, formData);
const toast = useToast();
const isDark = useDark();

const submit = () => {
    form.patch(route('customers.update', {customer: form.id!}), {
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
    router.get(route('customers.index'), route().queryParams, {preserveState: true});
};
</script>
<template>
    <FloatLabel></FloatLabel>
    <Head title="Edit Customer"/>
    <PageHeader/>
    <div class="m-4 p-4 bg-surface-50 dark:bg-surface-800 shadow sm:rounded-lg max-w-6xl">
        <Panel :pt="{ header: 'p-2 px-4' }">
            <template #header>
                <div class="flex items-center justify-between w-full gap-2 px-2">
                    <h2 class="text-lg font-medium uppercase">Edit Customer</h2>
                    <ButtonGroup>
                        <Button
                            icon="pi pi-caret-left"
                            size="small"
                            severity="secondary"
                            @click="router.get(route('customers.edit', previous?.id), undefined, { replace: true })"
                            :disabled="!previous"/>
                        <Button
                            icon="pi pi-caret-right"
                            size="small"
                            severity="secondary"
                            @click="router.get(route('customers.edit', next?.id), undefined, { replace: true })"
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
                            <label class="labelclass" for="address">Address</label>
                            <InputText
                                id="address"
                                v-model="form.address"
                                :class="form.errors.address ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.address"/>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="address2">Address Line 2</label>
                            <InputText
                                id="address2"
                                v-model="form.address2"
                                :class="form.errors.address2 ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.address2"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="city">City</label>
                            <InputText
                                id="city"
                                v-model="form.city"
                                :class="form.errors.city ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.city"/>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="state">State</label>
                            <InputText
                                id="state"
                                v-model="form.state"
                                :class="form.errors.state ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.state"/>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="country">Country</label>
                            <InputText
                                id="country"
                                v-model="form.country"
                                :class="form.errors.country ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.country"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="zip_code">Pin Code</label>
                            <InputText
                                id="zip_code"
                                v-model="form.zip_code"
                                :class="form.errors.zip_code ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.zip_code"/>
                        </div>
                        <div class="flex flex-col gap-1 max-w-md">
                            <label class="labelclass" for="mobile">Mobile</label>
                            <InputGroup>
                                <InputGroupAddon :pt="{ root: '!p-1' }">+91</InputGroupAddon>
                                <InputText
                                    id="mobile"
                                    v-model="form.mobile"
                                    :class="form.errors.mobile ? '!border-red-300' : ''"/>
                            </InputGroup>
                            <FormError :message="form.errors.mobile"/>
                        </div>
                        <div class="flex flex-col gap-1 max-w-md">
                            <label class="labelclass" for="phone">Phone</label>
                            <InputText
                                id="phone"
                                v-model="form.phone"
                                :class="form.errors.phone ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.phone"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="morning_milk">Morning Milk</label>
                            <InputNumber
                                inputId="morning_milk"
                                v-model="form.morning_milk"
                                :useGrouping="false" :class="form.errors.morning_milk ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.morning_milk"/>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="morning_price">Morning Price</label>
                            <InputNumber
                                inputId="morning_price"
                                v-model="form.morning_price"
                                :useGrouping="false" :class="form.errors.morning_price ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.morning_price"/>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="evening_milk">Evening Milk</label>
                            <InputNumber
                                inputId="evening_milk"
                                v-model="form.evening_milk"
                                :useGrouping="false" :class="form.errors.evening_milk ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.evening_milk"/>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="labelclass" for="evening_price">Evening Price</label>
                            <InputNumber
                                inputId="evening_price"
                                v-model="form.evening_price"
                                :useGrouping="false" :class="form.errors.evening_price ? '!border-red-300' : ''"/>
                            <FormError :message="form.errors.evening_price"/>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 items-center gap-2">
                        <div class="flex flex-col gap-1">
                            <FloatLabel class="w-full" variant="on">
                                <Select
                                    v-model="form.invoice_series_id"
                                    inputId="invoice_series_id"
                                    :options="props.invoiceSeries"
                                    optionLabel="prefix"
                                    optionValue="id"
                                    class="w-full"
                                    :class="form.errors.invoice_series_id ? '!border-red-300' : ''"/>
                                <label for="dairy_id">Invoice Series</label>
                            </FloatLabel>
                            <FormError :message="form.errors.invoice_series_id"/>
                        </div>
                        <div class="flex gap-1">
                            <Checkbox v-model="form.active" inputId="active" name="active" binary/>
                            <label for="active">Active</label>
                            <FormError :message="form.errors.active"/>
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
