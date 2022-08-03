<script setup>
import invoicejson from '../invoicedata.json'
import {  onMounted, ref , watch} from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import AddVoice from '../components/AddInvoice.vue'

const invoice = ref(invoicejson);
const route = useRoute();
const isAddInvoice = ref(true);

const getInvoiceData = ()=>{
     axios.get(`http://127.0.0.1:8000/api/invoice/${route.params.id}`).then((data)=>{
       invoice.value = data.data
     });
}
onMounted(()=>{
 getInvoiceData();
})

const markAsPaid = (()=>{
 axios.post(`http://127.0.0.1:8000/api/invoice/update-status/${invoice.value.id}`).then((data)=>{
    data.status === 200 && (invoice.value.status = 'paid')
 });
})

const deleteInvoice = (()=>{
 axios.delete(`http://127.0.0.1:8000/api/invoice/${invoice.value.id}`).then((data)=>{
    data.status === 200 && (history.back())
 });
})

const toggleAddInvoice = (()=>{
    isAddInvoice.value = !isAddInvoice.value;
})
</script>

<template>
<div class="w-100">
<AddVoice v-if="isAddInvoice" />
<div class="invoice-detail text-color-moon ms-auto">

    <div class="d-flex flex-column">

        <div class="invoice-detail-header d-flex">
            <img class="delete-icon me-3 mt-1" src="../assets/icon-arrow-left.svg"/>
            <router-link to="/"><div class="invoice-title"><h6>Go Back</h6></div></router-link>
        </div>
<div class="status-container mb-3">
   <p class="status-title">Status</p>
   <p
      class="status-body"
      :class="[
      invoice.status === 'draft'
      ? 'draft'
      : invoice.status === 'pending'
      ? 'pending'
      : 'paid',
      ]"
      >
      <span class="status-circle">.</span> {{ invoice.status||'draft' }}
   </p>
   <div class="btn-container">

      <button
         class="btn btn-edit"  @click="$router.push({name: 'home', params: { id: invoice.id }})"
         >
      Edit
      </button>

      <button class="btn btn-delete" @click="deleteInvoice">Delete</button>
      <button
        v-if="invoice.status === 'pending'"
         class="btn btn-mark"
         @click="markAsPaid"
         >
      Mark as Paid
      </button>
   </div>
</div>
<div class="details">
   <div class="project-info">
      <p class="project-id">#{{ invoice.id }}</p>
      <p class="project-desc">{{ invoice.description }}</p>
   </div>
   <div class="adress">
      <p class="adress-street">{{ invoice.senderAddress.street }}</p>
      <p class="adress-city">{{ invoice.senderAddress.city }}</p>
      <p class="adress-postcode">{{ invoice.senderAddress.postCode }}</p>
      <p class="adress-country">{{ invoice.senderAddress.country }}</p>
   </div>
   <div class="date">
      <p class="date-label">Invoice Date</p>
      <p class="date-body">{{ invoice.created_at }}</p>
   </div>
   <div class="name">
      <p class="name-label">Bill to:</p>
      <p class="name-body">{{ invoice.clientName }}</p>
   </div>
   <div class="mail">
      <p class="mail-label">Sent to:</p>
      <p class="mail-body">{{ invoice.clientEmail }}</p>
   </div>
   <div class="due">
      <p class="due-label">Invoice Due</p>
      <p class="due-body">{{ invoice.paymentDue }}</p>
   </div>
   <div class="client-adress">
      <p class="client-street">{{ invoice.clientAddress.street }}</p>
      <p class="client-city">{{ invoice.clientAddress.city }}</p>
      <p class="client-postcode">{{ invoice.clientAddress.postCode }}</p>
      <p class="client-country">{{ invoice.clientAddress.country }}</p>
   </div>
   <div class="item-container">
      <p>Item Name</p>
      <p>QTY.</p>
      <p>Price</p>
      <p>Total</p>
      <div
         class="project-item"
         v-for="(item, index) in invoice.items"
         :key="index"
         >
         <p class="prj-text">{{ item.name }}</p>
         <p class="prj-text">{{ item.quantity }}</p>
         <p class="prj-text">
            &#8378; {{ item.price }}
         </p>
         <p class="prj-text">
            &#8378; {{ item.total }}
         </p>
      </div>
   </div>
   <div class="amount">
      <p class="amount-text">Total Amount</p>
      <p class="amount-number">
         &#8378; {{ invoice.total }}
      </p>
   </div>
</div>

    </div>

</div>
</div>

</template>

<style scoped>
.invoice-detail{
    padding-top: 60px;
display: flex;
    justify-content: center;
    width: 100%;
    font-size: 12px;
}
.status-card{
    padding: 20px;
}
.detail {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  gap: 20px;
  padding: 50px 150px 50px 220px;
  color: white;
}
.link {
  text-decoration: none;
}
.back-icon {
  width: 10px;
  height: 10px;
}
.back-text {
  color: white;
  font-weight: 700;
  margin-left: 20px;
}
.status-container {
  padding: 20px 24px;
  display: flex;
  align-items: center;
  background-color: #1e2238;
  border-radius: 8px;
}
.status-title {
  font-weight: 600;
  color: hsl(231, 75%, 93%);
  flex-basis: 60px;
}
.status-body {
  width: 105px;
  padding: 13px 0 13px 40px;
  border-radius: 6px;
  font-weight: 700;
  display: flex;
  align-items: center;
  position: relative;
}
.status-circle {
  font-size: 40px;
  position: absolute;
  left: 15px;
  top: -19px;
}
.draft {
  background-color: #292c45;
  color: rgb(224, 228, 251);
}
.pending {
  background-color: rgba(255, 145, 0, 0.06);
  color: rgb(255, 145, 0);
}
.paid {
  background-color: rgba(51, 215, 160, 0.06);
  color: rgb(51, 215, 160);
}
.btn-container {
  margin-left: auto;
}
.btn {
  padding: 16px 24px;
  border: none;
  border-radius: 24px;
  color: white;
  font-weight: 700;
  cursor: pointer;
  height: 50px;
}
.btn-edit {
  background-color: #252946;
  margin-left: auto;
}
.btn-edit:hover {
  background-color: #1b1d32;
}
.btn-delete {
  background-color: #ec5555;
  margin-left: 10px;
}
.btn-delete:hover {
  background-color: #fb999a;
}
.btn-mark {
  background-color: #7b5cfa;
  margin-left: 10px;
}
.btn-mark:hover {
  background-color: #9175ff;
}
.details {
  padding: 20px 30px;
  display: grid;
  background-color: #1e2238;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  border-radius: 8px;
}
.project-info {
  grid-column-start: 1;
  grid-column-end: 2;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
}
.project-id {
  font-size: 16px;
  font-weight: 700;
}
.date-body,
.due-body,
.name-body,
.mail-body {
  font-size: 15px;
  font-weight: 700;
}
.adress {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  justify-content: flex-end;
  grid-column-start: 3;
  grid-column-end: 4;
}
.date {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  grid-column-start: 1;
  grid-column-end: 2;
  gap: 10px;
}
.name {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  grid-column-start: 2;
  grid-column-end: 3;
  gap: 10px;
}
.mail {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  grid-column-start: 3;
  grid-column-end: 4;
  gap: 10px;
}
.due {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-end;
  grid-column-start: 1;
  grid-column-end: 2;
  gap: 10px;
}
.client-adress {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  grid-column-start: 2;
  grid-column-end: 3;
}
.item-container {
  grid-column-start: 1;
  grid-column-end: 4;
  grid-row-start: 4;
  grid-row-end: 5;
  padding: 20px;
  border-top-left-radius: 8px;
  border-top-right-radius: 8px;
  background-color: #252946;
  display: grid;
  grid-template-columns: 2fr repeat(3, 1fr);
  row-gap: 20px;
}
.project-item {
  grid-column-start: 1;
  grid-column-end: 5;
  display: grid;
  grid-template-columns: 2fr repeat(3, 1fr);
}
.prj-text {
  font-weight: 700;
}
.amount {
  grid-column-start: 1;
  grid-column-end: 4;
  grid-row-start: 5;
  grid-row-end: 6;
  padding: 20px 70px 20px 20px;
  border-bottom-left-radius: 8px;
  border-bottom-right-radius: 8px;
  background-color: #0d0e17;
  margin-top: -20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.amount-number {
  font-size: 20px;
  font-weight: 700;
}
@media screen and (max-width: 1024px) {
  .detail {
    padding: 100px 120px 20px 120px;
  }
}
@media screen and (max-width: 750px) {
  .detail {
    padding: 100px 20px 20px 20px;
  }
}
.invoice-title h6{
    color:#F8F8FB !important;
}
a{
        text-decoration: none;
}
</style>
