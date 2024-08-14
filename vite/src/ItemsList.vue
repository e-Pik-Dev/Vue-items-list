<script setup lang="ts">
import { fetcher } from "./utils/fetcher.ts";
import SpinnerIcon from "./assets/SpinnerIcon.svg";
import { computed } from "vue";

// modal global settings are expected to be provided via drupalSettings
// @ts-ignore
const globalSettings = window?.drupalSettings?.itemsList;

// get endpoint set in Drupal module options or use defaults
const endpoint =
  // @ts-ignore
  (globalSettings?.endpoint as string) ||
  "https://jsonplaceholder.typicode.com/posts";

// get max items number set in Drupal module options or use defaults
const maxItemNo =
  // @ts-ignore
  parseInt(globalSettings?.maxItemsNo as number) || 10;

const { isError, isFetching, data, error } = fetcher(endpoint);

const trimmedData = computed(() =>
  maxItemNo ? data.value?.slice(0, maxItemNo) : data.value,
);
</script>

<template>
  <div class="wrapper">
    <div v-if="isFetching" class="spinner-wrapper">
      <!--  show spinner when data fetching  -->
      <SpinnerIcon class="spinner" />
    </div>

    <div v-else-if="isError" class="error-message">
      <!--  show fetch error if exist  -->
      {{ error }}
    </div>

    <!-- show data  -->
    <ul v-else class="list">
      <li v-for="(item, index) in trimmedData" :key="index" class="list__item">
        <div v-for="(val, key) in item" :key="key" class="list__item-entry">
          <span class="list__item-key">{{ key }}:</span>
          <span>{{ val }}</span>
        </div>
      </li>
    </ul>
  </div>
</template>

<style scoped>
.wrapper {
  display: flex;
  flex-direction: column;
  flex-wrap: nowrap;
  height: 100%;

  & > * {
    display: flex;
    flex: 1 1 auto;
    justify-content: center;
  }
}

.list {
  display: flex;
  flex-direction: column;
}
.list__item + .list__item {
  margin-top: 1rem;
}
.list__item-entry {
  display: flex;
  gap: 5px;
}
.list__item-key {
  font-weight: bold;
}

.error-message {
  color: darkred;
}

.spinner {
  justify-self: center;
  align-self: center;

  animation: spin 1s linear infinite;
  color: var(--spinner-color, rgb(229 231 235));
  fill: var(--spinner-fill, #1c64f2);
  width: var(--spinner-size, 2rem);
  height: var(--spinner-size, 2rem);
}

@keyframes spin {
  100% {
    transform: rotate(1turn);
  }
}
</style>
