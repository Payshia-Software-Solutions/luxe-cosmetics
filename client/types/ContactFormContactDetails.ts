export interface ContactDetails {
    email: string;
    subscribe: boolean;
    [key: string]: string | boolean | number | null | undefined;
}