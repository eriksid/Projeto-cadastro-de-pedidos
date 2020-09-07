<template>
<div id="app">
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Menu</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item" v-bind:class="(menu == 'produtos')?'active':''">
          <a v-on:click="menu = 'produtos'" class="nav-link" href="#">Produtos <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item" v-bind:class="(menu == 'pedidos')?'active':''">
          <a v-on:click="menu = 'pedidos'" class="nav-link" href="#">Pedidos</a>
        </li>
      </ul>
    </div>
  </nav>

  <div v-if="menu == 'produtos'">
    <div class="heading">
      <h1>Produtos</h1>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Nome</th>
          <th scope="col">Preço</th>
          <th scope="col">Peso</th>
          <th scope="col">Editar</th>
          <th scope="col">Ação</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input class="form-control" type="text" v-model="name"></td>
          <td>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">R$</span>
              </div> <input class="form-control" type="text" v-model="price">
            </div>
          </td>
          <td>
            <div class="input-group mb-3">
              <input class="form-control" type="text" v-model="weight">
              <div class="input-group-append">
                <span class="input-group-text">Kg</span>
              </div>
            </div>
          </td>
          <td> - </td>
          <td><button class='btn btn-primary' @click="create"> <i class="fas fa-plus"></i> Cadastrar</button></td>
        </tr>
        <product-component v-for="product in products" v-bind="product" :key="product.id" @update="update" @delete="del"></product-component>
      </tbody>
    </table>
  </div>

  <div v-if="menu == 'pedidos'">
    <div class="heading">
      <h1>Pedidos</h1>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th scope="col">Produtos</th>
          <th scope="col">Preço toal</th>
          <th scope="col">Peso total</th>
          <th scope="col">Total de produtos</th>
          <th scope="col">Status</th>
          <th scope="col">Editar</th>
          <th scope="col" class="text-center" colspan="2">Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <multiselect @input="sum" v-model="idProducts" :multiple="true" :close-on-select="false" :options="listProducts" placeholder="Selecione os produtos"></multiselect>
          </td>
          <td>R$ {{total_price}}</td>
          <td> {{total_weight}} kg</td>
          <td> {{total}} </td>
          <td> orçamento </td>
          <td> - </td>
          <td><button class='btn btn-primary' @click="createOrder"> <i class="fas fa-plus"></i> Cadastrar</button></td>
        </tr>
        <order-component v-for="order in orders" v-bind="order" :key="order.id" v-bind:products="products" v-bind:listProducts="listProducts" @updateOrder="updateOrder" @updateStatus="updateStatus" @delete="delOrder"></order-component>
      </tbody>
    </table>
  </div>
</div>
</template>

<script>
function Product({
  id,
  name,
  weight,
  price
}) {
  this.id = id;
  this.name = name;
  this.weight = weight;
  this.price = price;
}

function Order({
  id,
  idProducts,
  total_weight,
  total_price,
  total,
  status
}) {
  this.id = id;
  this.idProducts = idProducts;
  this.total_weight = total_weight;
  this.total_price = total_price;
  this.total = total;
  this.status = status;
}

import ProductComponent from './components/Product.vue';
import OrderComponent from './components/Order.vue';

export default {
  data() {
    return {
      name: '',
      weight: '',
      price: '',
      idProducts: '',
      total_weight: 0,
      total_price: 0,
      total: 0,
      status: '',
      products: [],
      orders: [],
      listProducts: [],
      mute: false,
      menu: 'produtos'
    }
  },
  methods: {
    async create() {
      if (this.name == '') {
        alert('Nome: deve ser preenchido');
        return;
      }
      if (this.weight == '') {
        alert('Peso: deve ser preenchido');
        return;
      }
      if (this.price == '') {
        alert('Preço: deve ser preenchido');
        return;
      }
      this.mute = true;
      const {
        data
      } = await window.axios.post('/api/products', {
        name: this.name,
        weight: this.weight,
        price: this.price
      });
      this.name = '';
      this.weight = '';
      this.price = '';
      this.products.push(new Product(data));
      this.mute = false;
    },
    async createOrder() {
      if (this.idProducts == '') {
        alert('Produtos: deve ser preenchido');
        return;
      }

      this.mute = true;
      const {
        data
      } = await window.axios.post('/api/orders', {
        idProducts: this.idProducts.toString(),
        total_weight: this.total_weight,
        total_price: this.total_price,
        total: this.total,
        status: 'orçamento'
      });
      this.idProducts = '';
      this.total_weight = '';
      this.total_price = '';
      this.total = '';
      this.orders.push(new Order(data));
      this.mute = false;
    },
    async read() {
      this.mute = true;
      const {
        data
      } = await window.axios.get('/api/products');
      data.forEach(product => {
        this.products.push(new Product(product))
        this.listProducts.push(product.name);
      });

      this.mute = false;
    },
    async readOrders() {
      this.mute = true;
      const {
        data
      } = await window.axios.get('/api/orders');
      data.forEach(order => this.orders.push(new Order(order)));
      this.mute = false;
    },
    async update(id, name, weight, price) {
      if (name == '') {
        alert('Nome: deve ser preenchido');
        return;
      }
      if (weight == '') {
        alert('Peso: deve ser preenchido');
        return;
      }
      if (price == '') {
        alert('Preço: deve ser preenchido');
        return;
      }
      this.mute = true;
      await window.axios.put(`/api/products/${id}`, {
        name,
        weight,
        price
      });
      this.mute = false;
    },
    async updateOrder(id, idProducts, total_weight, total_price, total) {
      this.mute = true;
      await window.axios.put(`/api/orders/${id}`, {
        idProducts: idProducts,
        total_weight,
        total_price,
        total,
        status: 'orçamento'
      });
      this.mute = false;
    },
    async updateStatus(id, idProducts, total_weight, total_price, total) {
      this.mute = true;
      await window.axios.put(`/api/orders/${id}`, {
        idProducts: idProducts,
        total_weight,
        total_price,
        total,
        status: 'pedido'
      });
      let index = this.orders.findIndex(order => order.id === id);
      this.$set(this.orders, index, {
        idProducts,
        total_weight,
        total_price,
        total,
        status: 'pedido'
      })

      this.mute = false;
    },
    async del(id) {
      this.mute = true;
      await window.axios.delete(`/api/products/${id}`);
      let index = this.products.findIndex(product => product.id === id);
      this.products.splice(index, 1);
      this.mute = false;
    },
    async delOrder(id) {
      this.mute = true;
      await window.axios.delete(`/api/orders/${id}`);
      let index = this.orders.findIndex(order => order.id === id);
      this.orders.splice(index, 1);
      this.mute = false;
    },
    sum() {
      this.total_weight = 0;
      this.total_price = 0;
      this.total = 0;
      this.products.map(item => {
        this.idProducts.map(idProduct => {
          if (item.name == idProduct) {
            this.total_weight += Number(item.weight);
            this.total_price += Number(item.price);
            this.total++;
          }
        });

      });
    }
  },
  watch: {
    mute(val) {
      document.getElementById('mute').className = val ? "on" : "";
    }
  },
  components: {
    ProductComponent,
    OrderComponent,
    Multiselect: window.VueMultiselect.default
  },
  created() {
    this.read();
    this.readOrders();
  }
}
</script>

<style>
#app {
  margin-left: 1em;
}

.heading h1 {
  margin-bottom: 0;
  text-align: center;
}
</style>
