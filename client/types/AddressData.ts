// Define interfaces for our component
export interface AddressData {
    firstName: string;
    lastName: string;
    address: string;
    apartment: string; // Add this
    city: string;
    state?: string;
    country?: string;
    postalCode: string;
    phone?: string;
}