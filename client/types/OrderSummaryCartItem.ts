// Adjusted to match CartContext item structure
export interface CartItem {
    id: number | string;
    name: string;
    price: number;
    quantity: number;
    image?: string;
}