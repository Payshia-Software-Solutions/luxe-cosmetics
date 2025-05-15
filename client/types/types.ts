// Shared types for the filter components

import { JSX } from "react";

export interface FilterState {
  priceRange?: [number, number];
  categories?: string[];
  brands?: string[];
  ratings?: number[];
  onSale?: boolean;
  sort?: string;
}

export interface Category {
  icon: JSX.Element;
  label: string;
  count: number;
}