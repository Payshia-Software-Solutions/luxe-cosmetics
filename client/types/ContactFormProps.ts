import { ContactDetails } from "@/types/ContactFormContactDetails";
export interface ContactFormProps {
    setContactDetails: React.Dispatch<React.SetStateAction<ContactDetails>>;
}