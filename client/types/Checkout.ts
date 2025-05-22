import { CartItem } from "./CartItem";
export interface ContactDetails {
  email: string;
  subscribe: boolean;
}

export interface Address {
  firstName: string;
  lastName: string;
  address: string;
  apartment: string;
  city: string;
  state?: string;
  country?: string;
  postalCode: string;
  phone?: string;
}

// Define a proper CartItem interface (this fixes the lint error)
// export  interface CartItem {
//   id: number;
//   product_id?: number;
//   name: string;
//   price: number;
//   quantity: number;
//   image?: string;
//   // Add any other properties your cart items have
// }

export interface OrderData {
  items: CartItem[]; // Now using the proper type instead of any[]
  totalAmount: number;
  discountAmount: number;
  shippingFee: number;
  promoCode: string | number;
  paymentMethod: string;
  contactDetails: ContactDetails;
  shippingAddress: Address;
  billingAddress: Address;
  sameAddressStatus: number;
}


export interface ContactDetails {
  email: string;
  subscribe: boolean;
  [key: string]: string | boolean | number | null | undefined;
}


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
export interface BillingAddressFormProps {
  shippingAddress: AddressData;
  setBillingAddress: (address: AddressData) => void;
  setSameAddressStatus: (status: number) => void;
}


// Define the address data interface
export interface DeliveryAddressData {
  country: string;
  firstName: string;
  lastName: string;
  address: string;
  apartment: string;
  city: string;
  postalCode: string;
  phone: string;
}

// Define props interface for the component
export interface DeliveryFormProps {
  setDeliveryAddress: (address: DeliveryAddressData) => void;
}


export interface PromoCodeData {
  is_active: boolean;
  start_date: string;
  end_date: string;
  min_order_value: string;
  discount_type: "percentage" | "fixed";
  discount_value: string;
}

// Define interface for applicable product data
export interface ApplicableProductData {
  product_id: string | number;
}

// Define props interface for the component
export interface OrderSummaryProps {
  cart: CartItem[];
  finalAmount: number;
  shippingFee: number;
  setPromoCode: (code: string) => void;
  setFinalPayAmount: (amount: number) => void;
  setDiscountAmount: (amount: number) => void;
}
