// export interface Product {
//   id: number;
//   slug: string;
//   name: string;
//   price: number;
//   rating: number;
//   review: number;
//   description: string;
//   longDescription: string;
//   benefits: string[];
//   specifications: Record<string, string>;
//   ingredients: string;
//   images: string[];
//   category: string;
//   breadcrumbs: string[];
//   metaDescription: string;
//   reviews: Review[];
// }

// export interface Review {
//   id: number;
//   user: string;
//   rating: number;
//   date: string;
//   title: string;
//   comment: string;
//   verified: boolean;
//   helpful: number;
// }

// export const products: Product[] = [
//   {
//     id: 1,
//     slug: 'natural-glow-serum',
//     name: 'Natural Glow Serum',
//     price: 49.99,
//     rating: 4.8,
//     review: 128,
//     description: 'Our bestselling Natural Glow Serum is formulated with powerful antioxidants and natural ingredients to give your skin a radiant, healthy glow. This lightweight serum absorbs quickly and works for all skin types.',
//     longDescription: `
//       Transform your skincare routine with our Natural Glow Serum, a revolutionary formula designed to enhance your skin's natural radiance while providing deep nourishment and protection.

//       This lightweight yet powerful serum combines the latest in skincare technology with natural ingredients to deliver visible results. The fast-absorbing formula penetrates deeply into the skin, working at a cellular level to improve texture, tone, and overall skin health.
//       Perfect for all skin types, this serum can be used both morning and night as part of your daily skincare routine. Its non-comedogenic formula ensures it won't clog pores while delivering essential nutrients to your skin.
//     `,
//     benefits: [
//       'Brightens and evens skin tone',
//       'Reduces fine lines and wrinkles',
//       'Hydrates and nourishes',
//       'Protects against environmental damage'
//     ],
//     specifications: {
//       'Skin Type': 'All Skin Types',
//       'Size': '30ml / 1.0 fl oz',
//       'Usage': 'Morning and Evening',
//       'Storage': 'Store in a cool, dry place',
//       'Shelf Life': '24 months',
//       'Country of Origin': 'USA'
//     },
//     ingredients: 'Hyaluronic Acid, Vitamin C, Niacinamide, Peptides, Natural Extracts',
//     images: [
//       'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
//       'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
//       'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
//     ],
//     category: 'Serums',
//     breadcrumbs: ['Skincare', 'Treatments', 'Serums'],
//     metaDescription: 'Transform your skin with our Natural Glow Serum. This lightweight, fast-absorbing formula combines powerful antioxidants and natural ingredients for a radiant, healthy complexion.',
//     reviews: [
//       {
//         id: 1,
//         user: 'Sarah M.',
//         rating: 5,
//         date: '2024-02-15',
//         title: 'Amazing Results!',
//         comment: 'I\'ve been using this serum for a month and my skin has never looked better. The glow is real!',
//         verified: true,
//         helpful: 45
//       },
//       {
//         id: 2,
//         user: 'Emily R.',
//         rating: 4,
//         date: '2024-02-10',
//         title: 'Good but pricey',
//         comment: 'The serum works well and I can see improvement in my skin texture. A bit expensive though.',
//         verified: true,
//         helpful: 32
//       }
//     ]
//   },
// ];