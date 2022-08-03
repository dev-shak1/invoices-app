<script setup>
import invoicejson from '../invoicedata.json'
import { ref } from 'vue';
const invoiceData = ref(invoicejson);
console.log('Invoice data', invoiceData)
const saveData = (()=>{
  console.log("Change Data", invoiceData);
})

const getTitle = ((id)=>{
  return id? `Edit #${id}`: 'New Invoice'
})
const isRequired=false;
</script>

<template>
<div class="container add-voice-page " >
  <form class="text-color-moon">
    <div class="invoice-title new-invoice">
        <h1>{{getTitle(invoiceData.id)}}</h1>
    </div>
    <div class="mb-2 invoice-field-title">
        Bill From
    </div>
  <div class="mb-3 small-text">
    <label for="exampleInputEmail1" class="form-label">Street Address</label>
    <input type="text" v-model="invoiceData.senderAddress.street" :required="isRequired" class="form-control" id="street-address" aria-describedby="emailHelp">
  </div>
  <div class="d-flex">
    <div class="mb-3 small-text">
        <label for="exampleInputPassword1" class="form-label">City</label>
        <input type="text" v-model="invoiceData.senderAddress.city" class="form-control" id="city">
    </div>
        <div class="mb-3 small-text ms-3">
        <label for="exampleInputPassword1" class="form-label">Post Code</label>
        <input type="text" v-model="invoiceData.senderAddress.postCode" class="form-control" id="post-code">
    </div>
        <div class="mb-3 small-text ms-3">
        <label for="exampleInputPassword1" class="form-label">Country</label>
        <input type="text " v-model="invoiceData.senderAddress.country" class="form-control" id="country">
    </div>
  </div>
  <div class="mb-2 invoice-field-title">Bill To</div>
  <div class="mb-3 small-text">
    <label for="exampleInputEmail1" class="form-label">Client's Name</label>
    <input type="text" class="form-control" v-model="invoiceData.clientName" id="client-name" aria-describedby="emailHelp">
  </div>
  <div class="mb-3 small-text">
    <label for="exampleInputEmail1" class="form-label">Client's Email</label>
    <input type="email" class="form-control" v-model="invoiceData.clientEmail" id="client-email" aria-describedby="emailHelp">
  </div>
  <div class="mb-3 small-text">
    <label for="exampleInputEmail1" class="form-label">Street Address</label>
    <input type="text" class="form-control" v-model="invoiceData.clientAddress.street" aria-describedby="emailHelp">
  </div>


  <div class="d-flex">
    <div class="mb-3 small-text">
        <label for="exampleInputPassword1" class="form-label">City</label>
        <input type="text" class="form-control" v-model="invoiceData.clientAddress.city" >
    </div>
        <div class="mb-3 small-text ms-3">
        <label for="exampleInputPassword1" class="form-label">Post code</label>
        <input type="text" class="form-control" v-model="invoiceData.clientAddress.postCode" >
    </div>
        <div class="mb-3 small-text ms-3">
        <label for="exampleInputPassword1" class="form-label">Country</label>
        <input type="text" class="form-control" v-model="invoiceData.clientAddress.country">
    </div>
  </div>

  <div class="d-flex">
    <div class="mb-3 small-text w-100">
        <label for="exampleInputPassword1" class="form-label">Invoice date</label>
        <input type="date" class="form-control" v-model="invoiceData.created_at" >
    </div>
     <div class="mb-3 small-text ms-3 w-100">
        <label for="exampleInputPassword1" class="form-label">Payment terms</label>
        <!-- <input type="text" class="form-control" v-model="invoiceData.paymentTerms" > -->
        <select class="form-select form-control field" v-model="invoiceData.paymentTerms">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="7">7</option>
        </select>
    </div>
  </div>

  <div class="mb-3 small-text">
    <label for="exampleInputEmail1" class="form-label">Project Description</label>
    <input type="text" class="form-control" v-model="invoiceData.clientAddress.street" aria-describedby="emailHelp">
  </div>


<div class="item-list mb-5">
 <div class="list-item-title mb">Item List</div>
<table class="small-text w-100">
  <thead>
    <tr>
      <th scope="col">Item Name</th>
      <th scope="col">Qty.</th>
      <th scope="col">Price</th>
      <th scope="col">Total</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th class="td-container" scope="row">1</th>
      <td class="td-container">Mark</td>
      <td class="td-container">Otto</td>
      <td>@mdo</td>
      <td><img class="delete-icon" src="../assets/icon-delete.svg"/></td>
    </tr>
    <tr>
      <th class="td-container" scope="row">1</th>
      <td class="td-container">Mark</td>
      <td class="td-container">Otto</td>
      <td>@mdo</td>
      <td><img class="delete-icon" src="../assets/icon-delete.svg"/></td>
    </tr>
    <tr>
      <th class="td-container" scope="row">1</th>
      <td class="td-container">Mark</td>
      <td class="td-container">Otto</td>
      <td>@mdo</td>
      <td><img class="delete-icon" src="../assets/icon-delete.svg"/></td>
    </tr>
  </tbody>
</table>
<button class="round-button w-100 color-bg-252945 mt-1 text-color-moon small-text mb-3">
+ Add New Item
</button>

<div class="d-flex ">
  <button class="round-button  color-bg-252945 mt-1 small-text color-bg-F8F8FB">Discard</button>
  <div class="button-right-container">
     <button class="round-button color-bg-252945 mt-1 small-text text-color-moon color-bg-888EB0 me-2" @submit="saveData">Save As Draft</button>
      <button class="round-button  color-bg-252945 mt-1 small-text color-bg-7C5DFA text-color-moon" @click="saveData">Save & Send</button>
  </div>

</div>
</div>



</form>


</div>
</template>
<style scoped>
.add-voice-page{
    width: 30%;
    background-color: #1b1d30;
    min-height: 100vh;
    position: absolute;
    top: 0;
    z-index: 1;
    padding: 0px;
    border-bottom-right-radius: 15px;
    border-top-right-radius: 15px;
}
form{
    padding: 30px;
    padding-top: 60px;

}
.invoice-title, .new-invoice{
    margin-bottom: 30px;
}
.invoice-field-title{
    color: #7C5DFA;
    font-size: 12px;
}
form input, .field{
    background-color: #252945 !important;
    border: none;
}
.form-control:focus{
    outline: 0;
    box-shadow: 0 0 0 0.1rem rgb(13 110 253 / 25%);
}
.form-control{
    color:  #F8F8FB !important;
    font-size: 10px;
}
.list-item-title{
  font-weight: bold;
  font-size: 16px;
}

tr{
  height: 20px;
}
.td-container{
    padding: 5px;
    background-color: #252945;
    border-radius: 3px;
}
table{
  border-spacing: 10px;
  border-collapse: separate;
}
.button-right-container{
  display: flex;
  margin-left: auto;

}
</style>
