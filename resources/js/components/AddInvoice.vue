<script setup>
import invoicejson from '../invoicedata.json'
import { ref, watch, computed, onMounted } from 'vue';
import axios from 'axios'

const props = defineProps({
  invoice:Object
})



onMounted(()=>{
getInvoiceItems();
})

const invoiceItems = ref([])


const invoiceData = ref(props.invoice? props.invoice : (localStorage.hasOwnProperty('invoiceData'))? JSON.parse(localStorage.getItem('invoiceData')):invoicejson);
const localData  = ref(!props.invoice?  invoiceData : null);

/*
  get Invoice Items
*/
const getInvoiceItems = (()=>{
       axios.get(`http://127.0.0.1:8000/api/item`).then((data)=>{
       const response =data.data.data;
   
     
        invoiceData.value.items.forEach(element => {
          const index = response.findIndex((resItem)=>resItem.id === element.id || resItem.item_id === element.id)
          index > -1  && response.splice(index, 1)

        });
       invoiceItems.value = response;
     });
})


//Function use to validate all fields
const validateObject = ((object)=>{

  const {description, paymentTerms, clientName, clientEmail, senderAddress, clientAddress} = object;
  
  const isSenderAddress =  senderAddress.city&&senderAddress.street&&senderAddress.country&&senderAddress.postCode;
  
  const isClientAddress =  clientAddress.city&&clientAddress.street&&clientAddress.country&&clientAddress.postCode;
  
  if(!description || !paymentTerms || !clientName || !clientEmail || !isClientAddress || !isSenderAddress){
    return false;
  }
  
  return true;

});

//Setting localStorage changes 
watch(localData.value, (newObject) => {
  localStorage.setItem('invoiceData', JSON.stringify(newObject))
})


const reformateObject = ((object)=>{

  object.sender_street = object.senderAddress.street;
  object.sender_city = object.senderAddress.city;
  object.sender_postCode = object.senderAddress.postCode;
  object.sender_country = object.senderAddress.country;

  delete object.senderAddress;

  object.client_street = object.clientAddress.street;
  object.client_city = object.clientAddress.city;
  object.client_postCode = object.clientAddress.postCode;
  object.client_country = object.clientAddress.country;

 delete object.clientAddress;
  return object;
})


/*
  Saving Invoice 
*/

const saveData = ((e, save=false)=>{

   if(save && !validateObject(invoiceData.value)){
    alert('Validation error, All Fields are required!.');
    return;
   }
  const cloneInvoice = JSON.parse(JSON.stringify(invoiceData.value)); 

   
  save && (cloneInvoice.save = save)

  const saveInvoiceData = reformateObject(cloneInvoice);

  /*
  Invoice Update Call
  */
 if(cloneInvoice.id){
    axios.put(`http://127.0.0.1:8000/api/invoice/${cloneInvoice.id}`, saveInvoiceData).then((data)=>{
          if(!data.data.errors){
            location.reload();
            alert("Invoice Updated")
          }else{
            alert(data.data.message);
          }
      });
 }
 else{
      axios.post(`http://127.0.0.1:8000/api/invoice`, saveInvoiceData).then((data)=>{
        if(!data.data.errors){
          localStorage.removeItem('invoiceData')
          location.reload();
          alert("Invoice Added")
        }else{
          alert(data.data.message)
        }
      
      }).catch((error)=>{
        alert(error);
      });
 }

})

const getTitle = ((id)=>{
  return id? `Edit #${id}`: 'New Invoice'
})

const isRequired=ref(false);

  /*
  Adding Invoice Item
  */
const addItemInvoice = ((e)=>{
  const index = parseInt( e.target.value);
  if(index<0)return;
  if(invoiceItems.value[index]){
    const selectedItem = invoiceItems.value[index];
    selectedItem.quantity = 1;
    selectedItem.total = selectedItem.price;
    selectedItem.item_id = selectedItem.id;
    invoiceData.value.items.push(selectedItem)
  }
   e.target.value = '-1'
   invoiceItems.value && invoiceItems.value.splice(index, 1);
})

/*
 Delete Invoice Item
*/
const deleteInvoiceItem = ((e,index)=>{
 const{id, name, price} =  invoiceData.value.items[index];
invoiceData.value.items.splice(index, 1);
invoiceItems.value && invoiceItems.value.push({id, name, price})
})

const currentDate = new Date().toISOString().split('T')[0];

!props.invoice &&  (invoiceData.value.created_at = currentDate); 
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
    <input type="text" class="form-control" v-model="invoiceData.description" aria-describedby="emailHelp">
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
    <tr v-for="(item, index) in invoiceData?.items">
      <th class="td-container" scope="row">{{item.name}}</th>
          
      <td class="td-container w-25"><input type="number" v-model="item.quantity" min="0"  class="form-control" ></td>
      <td class="td-container">{{item.price}}</td>
      <td>{{item.quantity*item.price}}</td>
      <td @click="deleteInvoiceItem($event, index)"><img class="delete-icon" src="../assets/icon-delete.svg"/></td>
    </tr>
  </tbody>
</table>
     <div class="mt-1 small-text  w-100 mb-3" v-if="invoiceItems.length" >

        <select class="form-select form-control field add-button" @change="addItemInvoice">
          <option value="-1" selected> + Add New Item</option>
          <option v-for="(invoiceItem, index) in invoiceItems" :value="index">{{invoiceItem.name}}</option>
        </select>
    </div>


<div class="d-flex ">
  <button  class="round-button  color-bg-252945 mt-1 small-text color-bg-F8F8FB" >Discard</button>
  <div class="button-right-container">
     <button v-if="!props.invoice" class="round-button color-bg-252945 mt-1 small-text text-color-moon color-bg-888EB0 me-2"  @click.prevent="saveData">Save As Draft</button>
      <button v-if="!props.invoice" class="round-button  color-bg-252945 mt-1 small-text color-bg-7C5DFA text-color-moon" @click.prevent="saveData($event, true)">Save & Send</button>
      <button v-if="
      props.invoice" class="round-button  color-bg-252945 mt-1 small-text color-bg-7C5DFA text-color-moon" @click.prevent="saveData($event, true)">Save & Close</button>
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
    border-right: 2px solid;
}
form{
    padding: 30px;
    padding-top: 60px;
    max-height: 100vh;
    overflow-y: scroll;

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
.add-button{
  text-align: center;
  background-image:none;
}
</style>
