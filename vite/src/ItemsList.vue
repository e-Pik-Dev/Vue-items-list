<script setup lang="ts">
import {fetcher} from "./utils/fetcher.ts";
import SpinnerIcon from "./assets/SpinnerIcon.svg";
import {computed} from "vue";

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

const {isError, isFetching, data, error} = fetcher(endpoint);

const trimmedData = computed(() =>
    maxItemNo ? data.value?.slice(0, maxItemNo) : data.value,
);

// decide if the value pertains to the main info
// let's assume it's so when word count is more than 10
const isMainInfo = (val: string) => val?.split(/[\s,.]/).length > 10;
</script>

<template>
  <div class="component">
    <div v-if="isFetching" class="center-wrapper">
      <!-- show spinner when data fetching -->
      <SpinnerIcon class="spinner"/>
    </div>

    <div v-else-if="isError" class="center-wrapper">
      <div class="error-message">
        <!-- show fetch error if exist -->
        {{ error }}
      </div>
    </div>

    <!-- show data-->
    <template v-else>
      <TransitionGroup name="list" appear>
        <div
            v-for="(item, index) in trimmedData"
            :key="index"
            class="list"
            :style="`transition-delay: calc(0.1s + ${index} * 0.05s);`"
        >
          <template v-for="(val, key) in item" :key="key">
            <span v-if="isMainInfo(String(val))" class="list__item-main">
              {{ val }}
            </span>

            <span v-else class="list__item">
              <span class="list__item-key">{{ key }}:</span>
              <span class="list__item-val">{{ val }}</span>
            </span>
          </template>
        </div>
      </TransitionGroup>
    </template>
  </div>
</template>

<style scoped>
.component {
  font-family: var(--component-font-family);
  font-size: var(--component-font-size);

  background-color: var(--component-bg-color);
  max-width: var(--component-max-width);
  padding: var(--component-padding);

  position: relative;
  min-height: var(--spinner-size);

  display: grid;

  /* mobile first layout */
  gap: 0.5rem;
  grid-template-columns: repeat(1, minmax(0, 1fr));

  /* larger screens layout */
  @media (min-width: 768px) {
    gap: 1rem;
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

.list {
  background-color: var(--card-background-color);
  border-width: var(--card-border-width);
  border-color: var(--card-border-color);
  border-style: var(--card-border-style);
  border-radius: var(--card-border-radius);
  padding: var(--card-padding);
}

.list__item-main {
  display: block;
  color: var(--card-main-item-color);
  line-height: var(--card-main-item-line-height);
  font-size: var(--card-main-item-font-size);
  font-weight: var(--card-main-item-font-weight);
  padding-top: var(--card-main-item-padding-top);
}

.list__item {
  color: var(--card-item-color);
  line-height: var(--card-item-line-height);
  font-size: var(--card-item-font-size);
  font-weight: var(--card-item-font-weight);
}
.list__item + .list__item {
  margin-left: var(--card-item-padding-end);
  position: relative;
}
.list__item + .list__item:before {
  content: "";
  position: absolute;
  border-left: 2px solid var(--card-item-color);
  opacity: 0.25;
  left: calc(var(--card-item-padding-end) * -1 - 1px);
  top: 50%;
  height: calc(var(--card-item-line-height) * 0.75);
  transform: translate(calc(var(--card-item-padding-end) * 0.5), -50%);
}
.list__item-key {
  opacity: 0.7;
  margin-right: calc(var(--card-item-padding-end) * 0.25);
}

.error-message {
  color: darkred;
}

.center-wrapper {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}
.spinner {
  animation: spin 1s linear infinite;
  color: var(--spinner-color);
  fill: var(--spinner-fill);
  width: var(--spinner-size);
  height: var(--spinner-size);
}

@keyframes spin {
  100% {
    transform: rotate(1turn);
  }
}

.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
}
</style>