import { reactive, ToRefs, toRefs, UnwrapNestedRefs } from "vue";

export interface FetchState {
  isError: boolean;
  isFetching: boolean;
  data: Object[] | null;
  error: string | null;
}

/**
 * Opinionated simple fetcher, that uses Fetch API simulating behaviour of TanStackQuery/RTK Query data fetchers
 * @param {string} endpoint Endpoint url string to fetch data from
 * @returns [FetchState] Reactive object  compatible with destructuring
 */
export const fetcher = (
  endpoint: string,
): ToRefs<UnwrapNestedRefs<FetchState> & {}> => {
  // define fetcher states
  const fetcherState = reactive<FetchState>({
    isError: false,
    isFetching: true,
    data: null,
    error: null,
  });

  // make fetching in an async flow
  setTimeout(async () => {
    const endpointURL = new URL(endpoint);
    const response = await fetch(endpointURL);

    if (!response.ok) {
      fetcherState.isFetching = false;
      fetcherState.isError = true;
      fetcherState.error = "Bad network response";
    }

    let jsonData;

    try {
      jsonData = await response.json();
    } catch (error) {
      const typedError = error as Error;
      fetcherState.isFetching = false;
      fetcherState.isError = true;
      fetcherState.error = typedError.message;
    }

    fetcherState.data = jsonData;
    fetcherState.isFetching = false;
  });

  // return the state without loosing reactivity on destructuring
  return toRefs(fetcherState);
};
