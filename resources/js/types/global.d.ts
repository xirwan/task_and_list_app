// resources/js/types/global.d.ts

import { Config } from 'ziggy-js';

declare global {
  function route(
    name?: string,
    params?: any,
    absolute?: boolean,
    config?: Config,
  ): string;
}