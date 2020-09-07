<template>
<tr>
  <td>
    <multiselect v-if="!isHidden" @input="sum" v-model="idProducts" :multiple="true" :close-on-select="false" :options="listProducts" placeholder="Selecione os produtos"></multiselect>
    <span v-if="isHidden">{{idProducts}}</span>
  </td>
  <td>R$ {{total_price}}</td>
  <td> {{total_weight}} kg</td>
  <td> {{total}} </td>
  <td> {{status}} </td>
  <td> <span v-on:click="isHidden = false" class="btn text-primary"> <i class="fas fa-edit"></i>Editar</span></td>
  <td v-if="isHidden"> <button class="btn text-danger" @click="del"><i class="fas fa-trash"></i> Excluir</button></td>
  <td v-if="!isHidden" v-on:click="isHidden = true"> <button class="btn text-primary" @click="updateOrder"><i class="fas fa-save"></i> Salvar</button></td>
  <td v-if="status =='orçamento'"> <button class=" btn text-success" @click="updateStatus"><i class="fas fa-check"></i> Finalizar Pedido</button></td>
</tr>
</template>

<script>
export default {
  data() {
    return {
      isHidden: true,
      mute: false
    }
  },
  computed: {

  },
  methods: {
    updateOrder(val) {
      this.idProducts = this.idProducts.toString();
      this.$emit('updateOrder', this.id, this.idProducts, this.total_weight, this.total_price, this.total);
    },
    updateStatus(val) {
      this.idProducts = this.idProducts.toString();
      this.$emit('updateStatus', this.id, this.idProducts, this.total_price, this.total_weight, this.total);
    },
    del() {
      this.$emit('delete', this.id);
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
  components: {
    Multiselect: window.VueMultiselect.default
  },
  props: ['id', 'idProducts', 'total_price', 'total_weight', 'total', 'status', 'products', 'listProducts']
}
</script>

<style>
</style>
