import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    is_staff_member: boolean;
    credit_balance: number;
    timezone: string;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface SupportRequest {
    id: string;
    user_id: number;
    assigned_staff_user_id: number | null;
    status: 'open' | 'in_progress' | 'closed';
    category: string;
    title: string;
    preferred_assistance_type: string;
    preferred_date: string;
    preferred_time: string;
    timezone: string;
    staff_notes: string | null; // this is only shown to staff
    additional_category_info: string | null;
    additional_details: string;
    resolved_at: string | null;
    calculated_cost: number | null;
    formatted_category: string;
    formatted_status: string;
    formatted_preferred_date: string;
    formatted_preferred_time: string;
    [key: string]: unknown; // This allows for additional properties...
}
