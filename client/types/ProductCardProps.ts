// Updated Product interface to match the new data structure
import { Product } from "@/types/product";
export interface ProductCardProps {
    product: Product;
    onAddToCart: (productId: number) => void;
    onToggleWishlist: (productId: number) => void;
    isInWishlist: boolean;
}