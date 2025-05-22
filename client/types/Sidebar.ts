import { JSX } from "react";


 export  interface FilterState {
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

export interface SideBarProps {
  onFilterChange: (filterType: string, value: any) => void;
  activeFilters: FilterState;
}


export interface CartRowProps {
  item: {
    id: number;
    name: string;
    price: number;
    quantity: number;
    image?: string; // Using optional property syntax (?) which is equivalent to string | undefined
  };
  onQuantityChange: (id: number, delta: number) => void;
  onRemove: (id: number) => void;
}


export interface SortDropdownProps {
  onChange: (sortOption: string) => void;
  currentSort: string;
}
