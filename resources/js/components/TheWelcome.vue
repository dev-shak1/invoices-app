<script setup>
import invoices from '../invoices.json'
import AddVoice from './AddInvoice.vue'
import axios from 'axios';
import {  onMounted, ref , watch} from 'vue'

let invoicesData = ref([]);
const isAddInvoice = ref(false);

const getAllInvoiceData = ()=>{
     axios.get('http://127.0.0.1:8000/api/invoice').then((data)=>{
       const response =data.data.data;
        invoicesData.value = response;
     });
}
onMounted(()=>{
 getAllInvoiceData();
})

const isIconMoon = true;
// console.log(invoices);
// const {data} = invoices;

const getStatus = ((status)=>{
  let cardStatus = 'status-progress'
  if(status === 'Paid'){
    cardStatus = 'status-paid'
  }else if(status === 'Draft'){
cardStatus = 'status-draft'
  }
  return cardStatus;
})

const getBackgroundColor= ((status)=>{
  let cardStatus = 'background-color-orange'
  if(status === 'Paid'){
    cardStatus = 'background-color-green'
  }else if(status === 'Draft'){
cardStatus = 'background-color-black'
  }
  return cardStatus;
})

const toggleAddInvoice = (()=>{
    isAddInvoice.value = !isAddInvoice.value;
})

</script>

<template>
<div class="w-100" :class="isIconMoon?'color-moon':'color-sun'">
<AddVoice v-if="isAddInvoice" />
  <div v-if="true" class="invoice-page">
    <div class="invoice-page-body">
      <div class="invoice-page-header">
        <div class="invoice-title" :class="isIconMoon?'text-color-moon':'text-color-sun'">
          <h1>Invoices</h1>
          <p class="small-text">invoices</p>
        </div>
        <div class="invoice-page-header-right">
          <p class="small-text" :class="isIconMoon?'text-color-moon':'text-color-sun'">Filter by status <span><img class="arrow-down-icon" src="../assets/icon-arrow-down.svg" alt=""></span></p>
          <div @click="toggleAddInvoice" class="new-invoice-icon"><span><img class="svg-icon" src="../assets/white-add.svg"/></span>New Invoice</div>
        </div>

      </div>
      <div class="invoice-page-content">
        <div v-for="invoice in invoicesData" class="card" style="margin-bottom:10px" :class="isIconMoon?'color-moon-card text-color-moon':'color-sun-card text-color-sun'"  @click="$router.push({name: 'detail', params: { id: invoice.id },})">
          <div class="card-body">
            <div><b>#{{invoice.id}}</b></div>
            <div>Due {{invoice.paymentDue}}</div>
            <div>{{invoice.clientName}}</div>
            <div><b>{{invoice.total}}</b></div>
            <div class="d-flex card-status">
            <div class="status card" :class="getStatus(invoice.status)">
              <div class="status-background" :class="getBackgroundColor(invoice.status)"></div>
              <div class="status-dot" :class="getBackgroundColor(invoice.status)"></div>
              <div>{{invoice.status}}</div>

            </div>
                                                <div style="margin-left:10px"><img src="../assets/icon-arrow-right.svg"/></div>
            </div>


          </div>

        </div>
      </div>
      <div>

      </div>
    </div>


  </div>

</div>

</template>

<style scoped>


.invoice-page{
  padding-top: 60px;
  width: 100%;
  height: 100%;
  }
  .invoice-page-body{
    margin: auto;
    width: 60%;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .invoice-page-header{
    display: flex;
    flex-direction: row;

  }
  .text-color-moon{
    color:#F8F8FB;
  }
 .text-color-sun{
    color:#141625;
  }
  .invoice-title h1{
    font-size: 24px;
    font-weight: bold;
  }
  .small-text{
    font-size: 10px;
  }
  .invoice-page-header-right{
    margin-left: auto;
    display: flex;
    flex-direction: row;
  }
.invoice-page-header-right .small-text{
  margin-top: 7px;
}
  .arrow-down-icon{
    margin-left: 10px;
  }
  .new-invoice-icon{
    font-size: 10px;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 10px;
    height: 30px;
    border-radius: 24px;
    background-color: #7C5DFA;
    color: #F8F8FB;
    margin-left: 25px;
  }
  .new-invoice-icon span{
    margin-right: 7px;
  }
  .svg-icon{
    width: 16px;
    height: 16px;
  }
  .invoice-page-content{
    margin-top: 50px;
  }
  .invoice-page-content card{
    border: none;
  }
  .invoice-page-content .card .card-body{
    padding: 10px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
  }
  .invoice-page-content .color-moon-card{
    background-color: #252945;
  }
  .invoice-page-content .color-sun-card{
    background-color: white;
  }
  .invoice-page-content .card .card-body{
    font-size: 12px;
  }
  .invoice-page-content .card .card-body .status{
    display: flex;
    flex-direction: row;
    padding: 10px;
    justify-content: center;
    align-items: center;
    background-color: inherit;
    border: none;
  }
  .status-dot{
    width: 7px;
    height: 7px;
    border-radius: 50px;
    margin-right: 6px;
    background-color: inherit;
  }
  .status-progress{

    color: orange;
  }
  .status-paid{

    color: lightgreen;
  }
  .status-draft{

    color: black;
  }
  .invoice-page-content .card .card-body .status .status-background{
    width: 100%;
    height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0.1;
    border: none;
    border-radius: 5px;
  }
  .background-color-green{
    background-color: lightgreen;
  }
  .background-color-orange{
    background-color: orange;
  }
 .background-color-black{
  background-color: black;
  }
  .card-status{
    justify-content: center;
    align-items: center;
  }
</style>
