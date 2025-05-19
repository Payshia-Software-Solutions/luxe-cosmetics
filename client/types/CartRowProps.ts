import { CartItem } from '@/types/CartContextCartItem';

export interface CartRowProps {
    item: CartItem;  // Use your existing CartItem interface
    onQuantityChange: (id: number, delta: number) => void;
    onRemove: (id: number) => void;
}