import { Review } from './Review';
export interface FeaturedProducts_Product {
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