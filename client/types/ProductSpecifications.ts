export interface ProductSpecifications {
    ingredients?: string[];
    skin_type?: string[];
    [key: string]: string[] | string | undefined;
}