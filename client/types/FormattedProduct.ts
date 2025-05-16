import { ProductReview } from "@/types/ProductReview";
import { ProductSpecifications } from "@/types/ProductSpecifications";
export interface FormattedProduct {
    id: number;
    name: string;
    slug: string;
    category: string;
    price: number;
    rating: number;
    reviews: ProductReview[];
    description: string;
    longDescription: string;
    benefits: string[];
    specifications: ProductSpecifications;
    images: string[];
    breadcrumbs: string[];
}