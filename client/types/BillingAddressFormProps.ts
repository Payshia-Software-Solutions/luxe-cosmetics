import { AddressData } from "@/types/AddressData";
export interface BillingAddressFormProps {
    shippingAddress: AddressData;
    setBillingAddress: (address: AddressData) => void;
    setSameAddressStatus: (status: number) => void;
}