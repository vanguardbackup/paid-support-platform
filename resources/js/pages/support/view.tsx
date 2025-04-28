import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type SupportRequest, type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { Card, CardContent } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import StaffManagementConsole from "@/components/staff-management-console";
import {
    TicketIcon,
    CalendarIcon,
    ClockIcon,
    GlobeIcon,
    UserIcon,
    MailIcon,
    FileTextIcon,
    MessageSquareIcon,
    ArrowLeftIcon,
    NetworkIcon
} from "lucide-react";

interface StaffMember {
    id: number;
    name: string;
    avatar?: string;
}

interface ViewSupportRequest extends SupportRequest {
    submitter: {
        name: string;
        email: string;
    };
    ip_address?: string;
}

interface SupportPageProps extends SharedData {
    supportRequest: ViewSupportRequest;
    availableStaffMembers?: StaffMember[];
    timeInStaffTimezone?: string;
}

export default function ViewingRequest() {
    const page = usePage<SupportPageProps>();
    const { auth, supportRequest, availableStaffMembers = [], timeInStaffTimezone } = page.props;
    const isStaff = auth.user.is_staff_member;

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Support Home',
            href: '/support',
        },
        {
            title: 'Viewing Request',
            href: route('support.show', { id: supportRequest.id }),
        },
    ];

    const getStatusVariant = (status: string): "open" | "default" | "destructive" | "inprogress" => {
        switch(status) {
            case 'open': return 'open';
            case 'in_progress': return 'inprogress';
            case 'closed': return 'destructive';
            default: return 'default';
        }
    };

    const isOtherCategory = supportRequest.category === 'other';

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Viewing Request" />
            <div className="my-6 px-4 sm:px-0">
                <div className="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                    <div>
                        <h1 className="text-2xl sm:text-3xl font-bold break-words">{supportRequest.title}</h1>
                        <div className="flex flex-wrap items-center gap-2 mt-2">
                            <Badge variant={getStatusVariant(supportRequest.status)}>
                                {supportRequest.formatted_status}
                            </Badge>
                            <span className="text-sm text-muted-foreground">ID: {supportRequest.id.substring(0, 8)}</span>

                            {supportRequest.ip_address && (
                                <div className="flex items-center gap-1 text-sm text-muted-foreground">
                                    <NetworkIcon size={14} />
                                    <span>{supportRequest.ip_address}</span>
                                </div>
                            )}
                        </div>
                    </div>
                    <Link href={route('support.index')}>
                        <Button variant="outline" className="flex items-center gap-2">
                            <ArrowLeftIcon size={16} />
                            <span>Back to Support</span>
                        </Button>
                    </Link>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <Card className="lg:col-span-2">
                        <CardContent className="p-6">
                            <h2 className="text-xl font-semibold mb-4">Request Details</h2>
                            <div className="space-y-6">
                                <div>
                                    <h3 className="text-sm font-medium text-muted-foreground mb-2">Description</h3>
                                    <div className="max-h-64 overflow-y-auto rounded-md bg-muted/30 p-4">
                                        <p className="text-foreground break-words whitespace-pre-wrap">{supportRequest.additional_details}</p>
                                    </div>
                                </div>

                                {isOtherCategory && supportRequest.additional_category_info && (
                                    <div>
                                        <h3 className="text-sm font-medium text-muted-foreground mb-2">Additional Category Information</h3>
                                        <div className="max-h-40 overflow-y-auto rounded-md bg-amber-50 dark:bg-amber-950/30 p-4">
                                            <p className="text-foreground break-words whitespace-pre-wrap">{supportRequest.additional_category_info}</p>
                                        </div>
                                    </div>
                                )}

                                {supportRequest.staff_notes && isStaff && (
                                    <div className="pt-4 border-t border-border">
                                        <div className="flex items-center gap-2 mb-2">
                                            <MessageSquareIcon size={16} className="text-blue-500 dark:text-blue-400" />
                                            <h3 className="text-sm font-medium text-muted-foreground">Staff Notes</h3>
                                        </div>
                                        <div className="max-h-64 overflow-y-auto bg-muted/50 p-4 rounded-lg">
                                            <p className="text-foreground break-words whitespace-pre-wrap">{supportRequest.staff_notes}</p>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </CardContent>
                    </Card>

                    <div className="space-y-6">
                        <Card>
                            <CardContent className="p-6">
                                <div className="flex items-center gap-2 mb-4">
                                    <div className="bg-blue-100 dark:bg-blue-950 p-2 rounded-full">
                                        <TicketIcon size={18} className="text-blue-600 dark:text-blue-400" />
                                    </div>
                                    <h2 className="text-lg font-semibold">Ticket Information</h2>
                                </div>

                                <div className="space-y-3">
                                    <div className="flex items-start gap-3">
                                        <div className="bg-muted p-1.5 rounded shrink-0">
                                            <FileTextIcon size={16} className="text-muted-foreground" />
                                        </div>
                                        <div className="min-w-0">
                                            <p className="text-xs text-muted-foreground">Category</p>
                                            <p className="font-medium break-words">{supportRequest.formatted_category}</p>
                                        </div>
                                    </div>

                                    <div className="flex items-start gap-3">
                                        <div className="bg-muted p-1.5 rounded shrink-0">
                                            <CalendarIcon size={16} className="text-muted-foreground" />
                                        </div>
                                        <div className="min-w-0">
                                            <p className="text-xs text-muted-foreground">Preferred Date</p>
                                            <p className="font-medium break-words">{supportRequest.formatted_preferred_date}</p>
                                        </div>
                                    </div>

                                    <div className="flex items-start gap-3">
                                        <div className="bg-muted p-1.5 rounded shrink-0">
                                            <ClockIcon size={16} className="text-muted-foreground" />
                                        </div>
                                        <div className="min-w-0">
                                            <p className="text-xs text-muted-foreground">Preferred Time</p>
                                            <p className="font-medium break-words">
                                                {supportRequest.formatted_preferred_time}
                                                {timeInStaffTimezone && (
                                                    <span className="text-muted-foreground text-sm ml-2">
                                                        (Your time: {timeInStaffTimezone})
                                                    </span>
                                                )}
                                            </p>
                                        </div>
                                    </div>

                                    <div className="flex items-start gap-3">
                                        <div className="bg-muted p-1.5 rounded shrink-0">
                                            <GlobeIcon size={16} className="text-muted-foreground" />
                                        </div>
                                        <div className="min-w-0">
                                            <p className="text-xs text-muted-foreground">User's Timezone</p>
                                            <p className="font-medium break-words">{supportRequest.timezone}</p>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardContent className="p-6">
                                <div className="flex items-center gap-2 mb-4">
                                    <div className="bg-green-100 dark:bg-green-950 p-2 rounded-full">
                                        <UserIcon size={18} className="text-green-600 dark:text-green-400" />
                                    </div>
                                    <h2 className="text-lg font-semibold">Submitter Info</h2>
                                </div>

                                <div className="space-y-3">
                                    <div className="flex items-start gap-3">
                                        <div className="bg-muted p-1.5 rounded shrink-0">
                                            <UserIcon size={16} className="text-muted-foreground" />
                                        </div>
                                        <div className="min-w-0">
                                            <p className="text-xs text-muted-foreground">Name</p>
                                            <p className="font-medium break-words">{supportRequest.submitter.name}</p>
                                        </div>
                                    </div>

                                    <div className="flex items-start gap-3">
                                        <div className="bg-muted p-1.5 rounded shrink-0">
                                            <MailIcon size={16} className="text-muted-foreground" />
                                        </div>
                                        <div className="min-w-0">
                                            <p className="text-xs text-muted-foreground">Email</p>
                                            <p className="font-medium break-words">{supportRequest.submitter.email}</p>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        {isStaff && (
                            <StaffManagementConsole
                                supportRequest={supportRequest}
                                availableStaffMembers={availableStaffMembers}
                                currentUserId={auth.user.id}
                            />
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
