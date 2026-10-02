import { computed, type InjectionKey, type Ref } from 'vue';

export type LabelSize = 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11;
export const labelSizeKey = Symbol() as InjectionKey<Ref<LabelSize>>;
export const DEFAULT_LABEL_SIZE = computed<LabelSize>(() => 6);
