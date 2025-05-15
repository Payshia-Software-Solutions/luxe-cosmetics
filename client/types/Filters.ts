export interface Filters {
  priceRange?: [number, number];
  categories?: string[];
  brands?: string[];
  ratings?: number[];
  onSale?: boolean;
  sort?: string;
}
export interface FilterProps {
  filters: Filters;
  onFilterChange: (newFilters: Filters) => void;
}