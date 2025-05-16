import { DeliveryAddressData } from "@/types/DeliveryAddressData";
// Define props interface for the component
export interface DeliveryFormProps {
    setDeliveryAddress: (address: DeliveryAddressData) => void;
}