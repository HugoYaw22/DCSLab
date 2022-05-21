<template>
    <AlertPlaceholder :messages="alertErrors" />
    <div class="intro-y" v-if="mode === 'list'">
        <DataList :title="t('views.customer_group.table.title')" :data="customer_groupList" v-on:createNew="createNew" v-on:dataListChange="onDataListChange" :enableSearch="true">
           <template v-slot:table="tableProps">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">{{ t('views.customer_group.table.cols.code') }}</th>
                            <th class="whitespace-nowrap">{{ t('views.customer_group.table.cols.name') }}</th>
                            <th class="whitespace-nowrap">{{ t('views.customer_group.table.cols.remarks') }}</th>
                            <th class="whitespace-nowrap"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="tableProps.dataList !== undefined" v-for="(item, itemIdx) in tableProps.dataList.data">
                            <tr class="intro-x">
                                <td>{{ item.code }}</td>
                                <td><a href="" @click.prevent="toggleDetail(itemIdx)" class="hover:animate-pulse">{{ item.name }}</a></td>
                                <td>{{ item.remarks }}</td>
                                <td class="table-report__action w-12">
                                    <div class="flex justify-center items-center">
                                        <Tippy tag="a" href="javascript:;" class="tooltip p-2 hover:border" :content="t('components.data-list.view')" @click.prevent="showSelected(itemIdx)">
                                            <InfoIcon class="w-4 h-4" />
                                        </Tippy>
                                        <Tippy tag="a" href="javascript:;" class="tooltip p-2 hover:border" :content="t('components.data-list.edit')" @click.prevent="editSelected(itemIdx)">
                                            <CheckSquareIcon class="w-4 h-4" />
                                        </Tippy>
                                        <Tippy tag="a" href="javascript:;" class="tooltip p-2 hover:border" :content="t('components.data-list.delete')" @click.prevent="deleteSelected(itemIdx)">
                                            <Trash2Icon class="w-4 h-4 text-danger" />
                                        </Tippy>
                                    </div>
                                </td>
                            </tr>
                            <tr :class="{'intro-x':true, 'hidden transition-all': expandDetail !== itemIdx}">
                                <td colspan="6">
                                    <div class="flex flex-row">
                                        <div class="ml-5 w-48 text-right pr-5">{{ t('views.customer_group.fields.code') }}</div>
                                        <div class="flex-1">{{ item.code }}</div>
                                    </div>
                                    <div class="flex flex-row">
                                        <div class="ml-5 w-48 text-right pr-5">{{ t('views.customer_group.fields.name') }}</div>
                                        <div class="flex-1">{{ item.name }}</div>
                                    </div>
                                    <div class="flex flex-row">
                                        <div class="ml-5 w-48 text-right pr-5">{{ t('views.customer_group.fields.remarks') }}</div>
                                        <div class="flex-1">{{ item.remarks }}</div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <Modal :show="deleteModalShow" @hidden="deleteModalShow = false">
                    <ModalBody class="p-0">
                        <div class="p-5 text-center">
                            <XCircleIcon class="w-16 h-16 text-danger mx-auto mt-3" />
                            <div class="text-3xl mt-5">{{ t('components.data-list.delete_confirmation.title') }}</div>
                            <div class="text-slate-600 mt-2">
                                {{ t('components.data-list.delete_confirmation.desc_1') }}<br />{{ t('components.data-list.delete_confirmation.desc_2') }}
                            </div>
                        </div>
                        <div class="px-5 pb-8 text-center">
                            <button type="button" class="btn btn-outline-secondary w-24 mr-1" @click="deleteModalShow = false">
                                {{ t('components.buttons.cancel') }}
                            </button>
                            <button type="button" class="btn btn-danger w-24" @click="confirmDelete">{{ t('components.buttons.delete') }}</button>
                        </div>
                    </ModalBody>
                </Modal>
            </template>
        </DataList>
    </div>

    <div class="intro-y box" v-if="mode !== 'list'">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 dark:border-dark-5">
            <h2 class="font-medium text-base mr-auto" v-if="mode === 'create'">{{ t('views.customer_group.actions.create') }}</h2>
            <h2 class="font-medium text-base mr-auto" v-if="mode === 'edit'">{{ t('views.customer_group.actions.edit') }}</h2>
        </div>
        <div class="loader-container">
            <VeeForm id="customer_groupForm" class="p-5" @submit="onSubmit" @invalid-submit="invalidSubmit" v-slot="{ handleReset, errors }">
                <div class="p-5">                    
                    <!-- #region Code -->
                    <div class="mb-3">
                        <label for="inputCode" class="form-label">{{ t('views.customer_group.fields.code') }}</label>
                        <div class="flex items-center">
                            <VeeField id="inputCode" name="code" type="text" :class="{'form-control':true, 'border-danger': errors['code']}" :placeholder="t('views.customer_group.fields.code')" :label="t('views.customer_group.fields.code')" rules="required" @blur="reValidate(errors)" v-model="customer_group.code" :readonly="customer_group.code === '[AUTO]'" />
                            <button type="button" class="btn btn-secondary mx-1" @click="generateCode" v-show="mode === 'create'">{{ t('components.buttons.auto') }}</button>
                        </div>
                        <ErrorMessage name="code" class="text-danger" />
                    </div>
                    <!-- #endregion -->

                    <!-- #region Name -->
                    <div class="mb-3">
                        <label for="inputName" class="form-label">{{ t('views.customer_group.fields.name') }}</label>
                        <VeeField id="inputName" name="name" type="text" :class="{'form-control':true, 'border-danger': errors['name']}" :placeholder="t('views.customer_group.fields.name')" :label="t('views.customer_group.fields.name')" rules="required" @blur="reValidate(errors)" v-model="customer_group.name" />
                        <ErrorMessage name="name" class="text-danger" />
                    </div>
                    <!-- #endregion -->

                    <!-- #region Max Open Invoice -->
                    <div class="mb-3">
                        <label for="inputMaxOpenInvoice" class="form-label">{{ t('views.customer_group.fields.max_open_invoice') }}</label>
                        <input id="inputMaxOpenInvoice" name="max_open_invoice" type="text" class="form-control" :placeholder="t('views.customer_group.fields.max_open_invoice')" v-model="customer_group.max_open_invoice" />
                    </div>
                    <!-- #endregion -->

                    <!-- #region Max Outstanding Invoice -->
                    <div class="mb-3">
                        <label for="inputMaxOutstandingInvoice" class="form-label">{{ t('views.customer_group.fields.max_outstanding_invoice') }}</label>
                        <input id="inputMaxOutstandingInvoice" name="max_outstanding_invoice" type="text" class="form-control" :placeholder="t('views.customer_group.fields.max_outstanding_invoice')" v-model="customer_group.max_outstanding_invoice" />
                    </div>
                    <!-- #endregion -->

                    <!-- #region Max Invoice Age -->
                    <div class="mb-3">
                        <label for="inputMaxInvoiceAge" class="form-label">{{ t('views.customer_group.fields.max_invoice_age') }}</label>
                        <input id="inputMaxInvoiceAge" name="max_invoice_age" type="text" class="form-control" :placeholder="t('views.customer_group.fields.max_invoice_age')" v-model="customer_group.max_invoice_age" />
                    </div>
                    <!-- #endregion -->

                    <!-- #region Payment Term -->
                    <div class="mb-3">
                        <label for="inputPaymentTerm" class="form-label">{{ t('views.customer_group.fields.payment_term') }}</label>
                        <textarea id="inputPaymentTerm" name="payment_term" type="text" class="form-control" :placeholder="t('views.customer_group.fields.payment_term')" v-model="customer_group.payment_term" rows="3"></textarea>
                    </div>
                    <!-- #endregion -->

                    <!-- #region Selling Point -->
                    <div class="mb-3">
                        <label for="inputSellingPoint" class="form-label">{{ t('views.customer_group.fields.selling_point') }}</label>
                        <input id="inputSellingPoint" name="selling_point" type="text" class="form-control" :placeholder="t('views.customer_group.fields.selling_point')" v-model="customer_group.selling_point" />
                    </div>
                    <!--  #endregion -->

                    <!-- #region Selling Point Multipler -->
                    <div class="mb-3">
                        <label for="inputSellingPointMultipler" class="form-label">{{ t('views.customer_group.fields.selling_point_multiple') }}</label>
                        <input id="inputSellingPointMultipler" name="selling_point_multiple" type="text" class="form-control" :placeholder="t('views.customer_group.fields.selling_point_multiple')" v-model="customer_group.selling_point_multiple" />
                    </div>
                    <!--  #endregion -->

                    <!-- #region Sell At Cost -->
                    <div class="mb-3">
                        <label for="sell_at_cost" class="form-label">{{ t('views.customer_group.fields.sell_at_cost') }}</label>
                        <VeeField as="select" id="sell_at_cost" name="sell_at_cost" :class="{'form-control form-select':true, 'border-danger': errors['sell_at_cost']}" v-model="customer_group.sell_at_cost" rules="required" @blur="reValidate(errors)">
                            <option value="">{{ t('components.dropdown.placeholder') }}</option>
                            <option v-for="c in sell_at_costDDL" :key="c.code" :value="c.code">{{ t(c.name) }}</option>
                        </VeeField>
                        <ErrorMessage name="sell_at_cost" class="text-danger" />
                    </div>
                    <!-- #endregion -->

                    <!-- #region Global Markup Percent -->
                    <div class="mb-3">
                        <label for="inputGlobalMarkupPercent" class="form-label">{{ t('views.customer_group.fields.global_markup_percent') }}</label>
                        <input id="inputGlobalMarkupPercent" name="global_markup_percent" type="text" class="form-control" :placeholder="t('views.customer_group.fields.global_markup_percent')" v-model="customer_group.global_markup_percent" />
                    </div>
                    <!-- #endregion -->

                    <!-- #region Global Markup Nominal -->
                    <div class="mb-3">
                        <label for="inputGlobalMarkupNominal" class="form-label">{{ t('views.customer_group.fields.global_markup_nominal') }}</label>
                        <input id="inputGlobalMarkupNominal" name="global_markup_nominal" type="text" class="form-control" :placeholder="t('views.customer_group.fields.global_markup_nominal')" v-model="customer_group.global_markup_nominal" />
                    </div>
                    <!-- #endregion -->

                    <!-- #region Price Markdown Percent -->
                    <div class="mb-3">
                        <label for="inputPriceMarkdownPercent" class="form-label">{{ t('views.customer_group.fields.price_markdown_percent') }}</label>
                        <input id="inputPriceMarkdownPercent" name="price_markdown_percent" type="text" class="form-control" :placeholder="t('views.customer_group.fields.price_markdown_percent')" v-model="customer_group.price_markdown_percent" />
                    </div>
                    <!-- #endregion -->

                    <!-- #region Price Markdown Nominal -->
                    <div class="mb-3">
                        <label for="inputPriceMarkdownNominal" class="form-label">{{ t('views.customer_group.fields.price_markdown_nominal') }}</label>
                        <input id="inputPriceMarkdownNominal" name="price_markdown_nominal" type="text" class="form-control" :placeholder="t('views.customer_group.fields.price_markdown_nominal')" v-model="customer_group.price_markdown_nominal" />
                    </div>
                    <!-- #endregion -->

                    <!-- #region Round On -->
                    <div class="mb-3">
                        <label for="inputRoundOn" class="form-label">{{ t('views.customer_group.fields.round_on') }}</label>
                        <div as="select" multiple v-slot="{ value }" :class="{'form-control':true, 'border-danger':errors['round_on[]']}" id="inputRoundOn" name="round_on[]" size="6" v-model="customer_group.selected_round_on" rules="required" :label="t('views.customer_group.fields.round_on')" @blur="reValidate(errors)">
                            <option v-for="r in round_onDDL" :key="r.hId" :value="r.hId" :selected="value.includes(r.hId)">{{ r.display_name }}</option>
                        </div>
                    </div>
                    <!-- #endregion -->

                    <!-- #region Round Digit -->
                    <div class="mb-3">
                        <label for="inputRoundDigit" class="form-label">{{ t('views.customer_group.fields.round_digit') }}</label>
                        <input id="inputRoundDigit" name="round_digit" type="text" class="form-control" :placeholder="t('views.customer_group.fields.round_digit')" v-model="customer_group.round_digit" />
                    </div>
                    <!-- #endregion -->

                    <!-- #region Remarks -->
                    <div class="mb-3">
                        <label for="inputRemarks" class="form-label">{{ t('views.customer_group.fields.remarks') }}</label>
                        <textarea id="inputRemarks" name="remarks" type="text" class="form-control" :placeholder="t('views.customer_group.fields.remarks')" v-model="customer_group.remarks" rows="3"></textarea>
                    </div>
                    <!-- #endregion -->

                    <!-- #region Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ t('views.customer_group.fields.status') }}</label>
                        <VeeField as="select" id="status" name="status" :class="{'form-control form-select':true, 'border-danger': errors['status']}" v-model="customer_group.status" rules="required" @blur="reValidate(errors)">
                            <option value="">{{ t('components.dropdown.placeholder') }}</option>
                            <option v-for="c in statusDDL" :key="c.code" :value="c.code">{{ t(c.name) }}</option>
                        </VeeField>
                        <ErrorMessage name="status" class="text-danger" />
                    </div>
                    <!-- #endregion -->
                </div>
                <div class="pl-5" v-if="mode === 'create' || mode === 'edit'">
                    <button type="submit" class="btn btn-primary w-24 mr-3">{{ t('components.buttons.save') }}</button>
                    <button type="button" class="btn btn-secondary" @click="handleReset(); resetAlertErrors()">{{ t('components.buttons.reset') }}</button>
                </div>
            </VeeForm>
            <div class="loader-overlay" v-if="loading"></div>
        </div>
        <hr/>
        <div>
            <button type="button" class="btn btn-secondary w-15 m-3" @click="backToList">{{ t('components.buttons.back') }}</button>
        </div>
    </div>
</template>

<script setup>
//#region Imports
import { onMounted, onUnmounted, ref, computed, watch } from "vue";
import axios from "@/axios";
import { useI18n } from "vue-i18n";
import { route } from "@/ziggy";
import dom from "@left4code/tw-starter/dist/js/dom";
import { useUserContextStore } from "@/stores/user-context";
import DataList from "@/global-components/data-list/Main";
import AlertPlaceholder from "@/global-components/alert-placeholder/Main";
import { getCachedDDL, setCachedDDL } from "@/mixins";
//#endregion

//#region Declarations
const { t } = useI18n();
//#endregion

//#region Data - Pinia
const userContextStore = useUserContextStore();
const selectedUserCompany = computed(() => userContextStore.selectedUserCompany );
//#endregion

//#region Data - UI
const mode = ref('list');
const loading = ref(false);
const alertErrors = ref([]);
const deleteId = ref('');
const deleteModalShow = ref(false);
const expandDetail = ref(null);
//#endregion

//#region Data - Views
const customer_groupList = ref({});
const customer_group = ref({
    code: '',
    name: '',
    use_max_open_invoice: '0',
    max_open_invoice: '0',
    use_max_outstanding_invoice: '0',
    max_outstanding_invoice: '0',
    use_max_invoice_age: '0',
    max_invoice_age: '0',
    payment_term: '0',
    selling_point: '0',
    selling_point_multiple: '0',
    sell_at_cost: '0',
    price_markup_percent: '0',
    price_markup_nominal: '0',
    price_markdown_percent: '0',
    price_markdown_nominal: '0',
    is_rounding: '0',
    round_on: '',
    round_digit: '0',
    remarks: '',
    cash: { 
        hId: '',
        name: '' 
    },
});
const statusDDL = ref([]);
const cashDDL = ref([]);
//#endregion

//#region onMounted
onMounted(() => {
    if (selectedUserCompany.value !== '') {
        getAllCustomerGroups({ page: 1 });
        getDDLSync();
    } else  {
        
    }

    setMode();
    
    getDDL();

    loading.value = false;
});

onUnmounted(() => {
    sessionStorage.removeItem('DCSLAB_LAST_ENTITY');
});
//#endregion

//#region Methods
const setMode = () => {
    if (sessionStorage.getItem('DCSLAB_LAST_ENTITY') !== null) createNew();
}

const getAllCustomerGroups = (args) => {
    customer_groupList.value = {};
    if (args.pageSize === undefined) args.pageSize = 10;
    if (args.search === undefined) args.search = '';

    let companyId = selectedUserCompany.value;

    axios.get(route('api.get.db.company.customer_group.read', { "companyId": companyId, "page": args.page, "perPage": args.pageSize, "search": args.search })).then(response => {
        customer_groupList.value = response.data;
        loading.value = false;
    });
}

const getDDL = () => {
    if (getCachedDDL('statusDDL') == null) {
        axios.get(route('api.get.db.common.ddl.list.statuses')).then(response => {
            statusDDL.value = response.data;
            setCachedDDL('statusDDL', response.data);
        });    
    } else {
        statusDDL.value = getCachedDDL('statusDDL');
    }
}

const getDDLSync = () => {
    axios.get(route('api.get.db.cash.cash.read.all_active', {
            companyId: selectedUserCompany.value,
            paginate: false
        })).then(response => {
            cashDDL.value = response.data;
    });
}

const onSubmit = (values, actions) => {
    loading.value = true;

    var formData = new FormData(dom('#customer_groupForm')[0]); 
    formData.append('company_id', selectedUserCompany.value);
    
    if (mode.value === 'create') {
        axios.post(route('api.post.db.company.customer_group.save'), formData).then(response => {
            backToList();
        }).catch(e => {
            handleError(e, actions);
        }).finally(() => {
            loading.value = false;
        });
    } else if (mode.value === 'edit') {
        axios.post(route('api.post.db.company.customer_group.edit', customer_group.value.hId), formData).then(response => {
            actions.resetForm();
            backToList();
        }).catch(e => {
            handleError(e, actions);
        }).finally(() => {
            loading.value = false;
        });
    } else { }
}

const handleError = (e, actions) => {
    //Laravel Validations
    if (e.response.data.errors !== undefined && Object.keys(e.response.data.errors).length > 0) {
        for (var key in e.response.data.errors) {
            for (var i = 0; i < e.response.data.errors[key].length; i++) {
                actions.setFieldError(key, e.response.data.errors[key][i]);
            }
        }
        alertErrors.value = e.response.data.errors;
    } else {
        //Catch From Controller
        alertErrors.value = {
            controller: e.response.status + ' ' + e.response.statusText +': ' + e.response.data.message
        };
    }
}

const invalidSubmit = (e) => {
    alertErrors.value = e.errors;
    if (dom('.border-danger').length !== 0) dom('.border-danger')[0].scrollIntoView({ behavior: "smooth" });
}

const reValidate = (errors) => {
    alertErrors.value = errors;
}

const emptyCustomerGroup = () => {
    return {
        code: '[AUTO]',
        name: '',
        use_max_open_invoice: '0',
        max_open_invoice: '0',
        use_max_outstanding_invoice: '0',
        max_outstanding_invoice: '0',
        use_max_invoice_age: '0',
        max_invoice_age: '0',
        payment_term: '0',
        selling_point: '0',
        selling_point_multiple: '0',
        sell_at_cost: '0',
        price_markup_percent: '0',
        price_markup_nominal: '0',
        price_markdown_percent: '0',
        price_markdown_nominal: '0',
        is_rounding: '0',
        round_on: '',
        round_digit: '0',
        remarks: '',
        cash: { 
            hId: '',
            name: '' 
        },
    }
}

const resetAlertErrors = () => {
    alertErrors.value = [];
}

const createNew = () => {
    mode.value = 'create';
    
    if (sessionStorage.getItem('DCSLAB_LAST_ENTITY') !== null) {
        customer_group.value = JSON.parse(sessionStorage.getItem('DCSLAB_LAST_ENTITY'));
        sessionStorage.removeItem('DCSLAB_LAST_ENTITY');
    } else {
        customer_group.value = emptyCustomerGroup();
    }
    customer_group.value.company.hId = _.find(companyDDL.value, { 'hId': selectedUserCompany.value });
}

const onDataListChange = ({page, pageSize, search}) => {
    getAllCustomerGroups({page, pageSize, search});
}

const editSelected = (index) => {
    mode.value = 'edit';
    customer_group.value = customer_groupList.value.data[index];
}

const deleteSelected = (index) => {
    deleteId.value = customer_groupList.value.data[index].hId;
    deleteModalShow.value = true;
}

const confirmDelete = () => {
    deleteModalShow.value = false;
    axios.post(route('api.post.db.company.customer_group.delete', deleteId.value)).then(response => {
        backToList();
    }).catch(e => {
        alertErrors.value = e.response.data;
    }).finally(() => {

    });
}

const showSelected = (index) => {
    toggleDetail(index);
}

const backToList = () => {
    resetAlertErrors();
    sessionStorage.removeItem('DCSLAB_LAST_ENTITY');

    mode.value = 'list';
    getAllCustomerGroups({ page: customer_groupList.value.current_page, pageSize: customer_groupList.value.per_page });
}

const toggleDetail = (idx) => {
    if (expandDetail.value === idx) {
        expandDetail.value = null;
    } else {
        expandDetail.value = idx;
    }
}

const generateCode = () => {
    if (customer_group.value.code === '[AUTO]') customer_group.value.code = '';
    else  customer_group.value.code = '[AUTO]'
}
//#endregion

//#region Computed
//#endregion

//#region Watcher
watch(selectedUserCompany, () => {
    if (selectedUserCompany.value !== '') {
        getAllCustomerGroups({ page: 1 });
        getDDLSync();
    }
});

watch(customer_group, (newV) => {
    if (mode.value == 'create') sessionStorage.setItem('DCSLAB_LAST_ENTITY', JSON.stringify(newV));
}, { deep: true });
//#endregion
</script>