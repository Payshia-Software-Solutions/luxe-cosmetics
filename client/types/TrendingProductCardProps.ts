import { Product } from '@/types/TrendingProductCardProduct';
export interface TrendingProductCardProps {
    product: Product;
    onToggleWishlist?: (productId: string) => void;
    isInWishlist?: boolean;
    salesCount?: number;
}