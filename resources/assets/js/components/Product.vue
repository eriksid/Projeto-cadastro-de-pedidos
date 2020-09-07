<template>
<tr>
  <td>
    <span v-if="isHidden">{{ name | properCase }} </span>
    <input v-if="!isHidden" class="form-control" type="text" v-model="name">
  </td>
  <td>
    <span v-if="isHidden">R$ {{ price }} </span>
    <div v-if="!isHidden" class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">R$</span>
      </div> <input class="form-control" type="text" v-model="price">
    </div>
  </td>
  <td>
    <span v-if="isHidden">{{ weight }} Kg</span>
    <div v-if="!isHidden" class="input-group mb-3">
      <input class="form-control" type="text" v-model="weight">
      <div class="input-group-append">
        <span class="input-group-text">Kg</span>
      </div>
    </div>
  </td>
  <td> <span v-on:click="isHidden = false" class="btn text-primary"> <i class="fas fa-edit"></i>Editar</span></td>
  <td v-if="isHidden"> <button class="btn text-danger" @click="del"><i class="fas fa-trash"></i> Excluir</button></td>
  <td v-if="!isHidden" v-on:click="isHidden = true"> <button class="btn text-primary" @click="update"><i class="fas fa-save"></i> Salvar</button></td>
</tr>
</template>

<script>
//const axios = require('axios')
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
    update(val) {
      this.$emit('update', this.id, this.name, this.weight, this.price);
    },
    del() {
      this.$emit('delete', this.id);
    }
  },
  props: ['id', 'name', 'weight', 'price'],
  filters: {
    properCase(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }
  }
}
</script>

<style>
</style>
