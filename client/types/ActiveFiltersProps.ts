export interface ActiveFiltersProps {
  activeFilters: {
    priceRange?: [number, number];
    categories?: string[];
    brands?: string[];
    ratings?: number[];
    onSale?: boolean;
    sort?: string;
  };
  onRemoveFilter: (type: string, value?: string | number) => void;
}