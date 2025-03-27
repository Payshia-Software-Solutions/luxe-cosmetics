// app/api/auth/[...nextauth]/route.ts
import NextAuth from 'next-auth';
import { authOptions } from '@/lib/auth'; // Adjust path based on your structure

export const GET = NextAuth(authOptions);
export const POST = NextAuth(authOptions);