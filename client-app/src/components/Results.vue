<template>
  <div class="my-10">
    <table class="table-fixed border-collapse w-full rounded-lg overflow-hidden shadow-md">
      <thead>
      <tr class="bg-blue-600 text-white">
        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Proveedor</th>
        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Precio (EUR)</th>
        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Precio con descuento</th>
        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Nota</th>
      </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
      <tr
          v-for="(item, index) in results"
          :key="index"
          class="hover:bg-blue-50 transition-colors duration-150"
          :class="index === 0 ? 'bg-green-50' : ''"
      >
        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ item.provider }}</td>
        <td class="px-6 py-4 text-sm text-gray-700">{{ item.price }}</td>
        <td class="px-6 py-4 text-sm text-gray-700">{{ item.withDiscount }}</td>
        <td class="px-6 py-4 text-sm">
        <span
            v-if="index === 0"
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
        >
          🏷️ Oferta más barata
        </span>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import useFormStore from "../stores/form.js";
import {storeToRefs} from "pinia";

const store = useFormStore();
const {results} = storeToRefs(store);

</script>
