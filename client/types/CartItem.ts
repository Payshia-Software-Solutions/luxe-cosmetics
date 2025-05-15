// Define a proper CartItem interface (this fixes the lint error)
export interface CartItem {
    id: number;
    product_id?: number;
    name: string;
    price: number;
    quantity: number;
    image?: string;
    // Add any other properties your cart items have
}