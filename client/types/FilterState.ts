export interface FilterState {
    priceRange?: [number, number];
    categories?: string[];
    brands?: string[];
    ratings?: number[];
    onSale?: boolean;
    sort?: string;
}