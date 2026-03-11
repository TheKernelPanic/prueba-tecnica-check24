<script setup>
import {onMounted} from "vue";
import {storeToRefs} from "pinia";
import useFormStore from "../../stores/form.js";
import axios from "axios";
import dayjs from "dayjs";
import Results from "../../components/Results.vue";

const store = useFormStore();
const {form, isLoading, results} = storeToRefs(store);

onMounted(() => {
  store.load();
});

function onSubmit() {
  store.persist();
  if (isValid()) {
    request();
  }
}

function request() {
  store.isLoading = true;
  const requestBody = {
    driver_age: calculateAge(store.form.birthDate),
    car_form: store.form.carForm,
    car_use: store.form.carUse
  };
  axios
      .post('http://localhost:8000/calculate', requestBody) // TODO: Define host as ENV variable
      .then(
          response => {
            store.isLoading = false;
            store.results = response.data;
          }
      )
      .catch(
          error => {
            console.error('Request failed', error)
          }
      );
}

function isValid() {
  if (form.birthDate === '') {
    return false;
  }
  if (form.carForm === '') {
    return false;
  }
  if (form.carUse === '') {
    return false;
  }
  return true;
}

function calculateAge(date) {
  if (!date) {
    return null;
  }
  const birth = dayjs(date);
  const today = dayjs();

  return today.diff(birth, 'year');
}

</script>

<template>
  <div class="w-6/12">
    <form @submit.prevent="onSubmit">
      <h1 class="my-5 text-xl font-bold text-center">
        Compara el seguro de tu coche
      </h1>

      <div class="input-field">
        <label>
          Fecha de nacimiento
        </label>
        <input type="date" v-model="form.birthDate" :disabled="isLoading"/>
      </div>

      <div class="input-field">
        <label>
          Tipo de coche
        </label>
        <select v-model="form.carForm" :disabled="isLoading">
          <option value="compacto">Compacto</option>
          <option value="suv">SUV</option>
          <option value="turismo">Turismo</option>
        </select>
      </div>

      <div class="input-field">
        <label>
          Uso del coche
        </label>
        <div class="flex items-center justify-center gap-6">
          <label class="flex items-center gap-2 cursor-pointer">
            <input
                type="radio"
                id="private"
                name="type"
                value="privado"
                class="w-4 h-4"
                v-model="form.carUse"
                :disabled="isLoading"
            />
            <span>Privado</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input
                type="radio"
                id="commercial"
                name="type"
                value="comercial"
                class="w-4 h-4"
                v-model="form.carUse"
                :disabled="isLoading"
            />
            <span>Comercial</span>
          </label>
        </div>
      </div>

      <div class="flex justify-center">
        <button type="submit" class="bg-blue-600 text-white font-bold px-4 py-2 uppercase" :disabled="isLoading">
          Calcular
        </button>
      </div>
    </form>

    <div class="mt-4" v-if="results.length">
      <Results :results="results"/>
    </div>
  </div>
</template>