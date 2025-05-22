
export interface CheckboxProps {
  id: string;
  label: React.ReactNode;
  checked?: boolean;
  onChange?: () => void;
}


export interface PriceFilterProps {
  min: number;
  max: number;
  value: [number, number];
  onChange?: (value: [number, number]) => void;
}


export interface SectionHeaderProps {
  title: string;
  isExpanded: boolean;
  count?: number;
  onToggle?: () => void;
}


export interface Filters {
  priceRange?: [number, number];
  categories?: string[];
  brands?: string[];
  ratings?: number[];
  onSale?: boolean;
  sort?: string;
}