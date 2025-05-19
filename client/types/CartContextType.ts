import { CartItem } from '@/types/CartContextCartItem';

export interface CartContextType {
  cartItems: CartItem[];
  addToCart: (itemToAdd: CartItem) => void;
  removeFromCart: (id: number) => void;  // Using number for ID
  updateQuantity: (id: number, delta: number) => void;  // Using number for ID
  clearCart: () => void;
  isCartOpen: boolean;
  toggleCart: () => void;
  openCart: () => void;
  closeCart: () => void;
  getCartCount: () => number;
  getTotalAmount: () => number;
}