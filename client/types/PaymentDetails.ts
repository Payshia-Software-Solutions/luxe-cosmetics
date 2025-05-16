// Define the interface for the payment details
export interface PaymentDetails {
    order_id: string;
    items: string;
    currency: string;
    amount: string;
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
    address: string;
    city: string;
    country: string;
    return_url: string;
    cancel_url: string;
    notify_url: string;
}