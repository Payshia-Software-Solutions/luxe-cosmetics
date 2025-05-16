import type { Product } from '@/data/products';
export interface RelatedProductsProps {
    products: Product[];
    currentProductId: string;
}