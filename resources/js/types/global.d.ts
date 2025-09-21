import { Config } from 'ziggy-js';

declare global {
  function route(
    name?: string,
    params?: unknown,
    absolute?: boolean,
    config?: Config,
  ): string;
}