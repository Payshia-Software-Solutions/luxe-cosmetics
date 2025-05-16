import { ContactDetails } from "@/types/ContactDetails";
import { Address } from "@/types/Address";
import { CartItem } from "@/types/CartItem";

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
