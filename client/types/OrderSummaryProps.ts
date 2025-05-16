import { CartItem } from "@/types/OrderSummaryCartItem";
// Define props interface for the component
export interface OrderSummaryProps {
    cart: CartItem[];
    finalAmount: number;
    shippingFee: number;
    setPromoCode: (code: string) => void;
    setFinalPayAmount: (amount: number) => void;
    setDiscountAmount: (amount: number) => void;
}