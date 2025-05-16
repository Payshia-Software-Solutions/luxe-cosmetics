import { Review } from '@/types/Review';
export interface Product2 {
    id: number;
    slug: string;
    name: string;
    price: number;
    rating: number;
    review: number;
    description: string;
    longDescription: string;
    benefits: string[];
    specifications: Record<string, string>;
    ingredients: string;
    images: string[];
    category: string;
    breadcrumbs: string[];
    metaDescription: string;
    reviews: Review[];
}