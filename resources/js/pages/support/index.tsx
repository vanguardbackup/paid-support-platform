import AppLayout from '@/layouts/app-layout';
import {type BreadcrumbItem, type SharedData, type SupportRequest} from '@/types';
import {Head, Link, usePage} from '@inertiajs/react';
import {Button} from "@/components/ui/button";
import { TicketIcon, InboxIcon, CoinsIcon, PlusCircleIcon } from "lucide-react";
import { Card, CardContent } from "@/components/ui/card";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
interface SupportPageProps extends SharedData {
    supportRequests: SupportRequest[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Support Home',
        href: '/support',
    },
];

export default function Index() {
    const page = usePage<SupportPageProps>();
    const { auth, supportRequests } = page.props;

    const openRequests = supportRequests.filter(req => req.status === 'open').length;
    const totalRequests = supportRequests.length;
    const supportCredits = 15;

    const getStatusVariant = (status: string): "open" | "default" | "destructive" | "inprogress" => {
        switch(status) {
            case 'open': return 'open';
            case 'in_progress': return 'inprogress';
            case 'closed': return 'destructive';
            default: return 'default';
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Support" />
            <div className="my-6">
                <h1 className="text-3xl font-bold">Welcome back, {auth.user.name}.</h1>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <Card>
                        <CardContent className="p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-muted-foreground font-medium">Open Support Requests</p>
                                    <p className="text-3xl font-bold mt-2">{openRequests}</p>
                                </div>
                                <div className="bg-blue-100 dark:bg-blue-950 p-3 rounded-full">
                                    <TicketIcon size={32} className="text-blue-600 dark:text-blue-400" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardContent className="p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-muted-foreground font-medium">Lifetime Support Requests</p>
                                    <p className="text-3xl font-bold mt-2">{totalRequests}</p>
                                </div>
                                <div className="bg-green-100 dark:bg-green-950 p-3 rounded-full">
                                    <InboxIcon size={32} className="text-green-600 dark:text-green-400" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardContent className="p-6">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-muted-foreground font-medium">Available Support Credits</p>
                                    <p className="text-3xl font-bold mt-2">{supportCredits}</p>
                                </div>
                                <div className="bg-pink-100 dark:bg-pink-950 p-3 rounded-full">
                                    <CoinsIcon size={32} className="text-pink-600 dark:text-pink-400" />
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
                <div className="flex flex-col md:flex-row gap-6 mt-6">
                    <Card className="md:w-2/3">
                        <CardContent className="p-6">
                            <h2 className="text-2xl font-bold">Open a Support Request</h2>
                            <p className="text-muted-foreground mt-2">Our team is ready to help you with any issues you're facing.</p>
                            <Link href={route('support.create')} className="mt-4">
                                <Button className="mt-4 flex items-center gap-2 cursor-pointer" type="button">
                                    <PlusCircleIcon size={16} />
                                    Open New Request
                                </Button>
                            </Link>
                        </CardContent>
                    </Card>
                    <Card className="md:w-1/3">
                        <CardContent className="p-6">
                            <h2 className="text-xl font-semibold">Resources</h2>
                            <p className="text-muted-foreground mt-2">Coming soon</p>
                        </CardContent>
                    </Card>
                </div>
                <div className="mt-6">
                    <h2 className="text-2xl font-bold mb-4">Your Support Requests</h2>
                    <Card>
                        <div className="overflow-x-auto">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>ID</TableHead>
                                        <TableHead>Subject</TableHead>
                                        <TableHead>Support Category</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead>Scheduled Date</TableHead>
                                        <TableHead>Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {supportRequests.length > 0 ? (
                                        supportRequests.map((request) => (
                                            <TableRow key={request.id}>
                                                <TableCell className="font-medium">#{request.id.substring(0, 8)}</TableCell>
                                                <TableCell>{request.title}</TableCell>
                                                <TableCell>{request.formatted_category}</TableCell>
                                                <TableCell>
                                                    <Badge variant={getStatusVariant(request.status)}>
                                                        {request.formatted_status}
                                                    </Badge>
                                                </TableCell>
                                                <TableCell>{request.formatted_preferred_date} {request.formatted_preferred_time}</TableCell>
                                                <TableCell>
                                                    <Link href={route('support.show', request.id)}>
                                                        <Button variant="outline" size="sm">View</Button>
                                                    </Link>
                                                </TableCell>
                                            </TableRow>
                                        ))
                                    ) : (
                                        <TableRow>
                                            <TableCell colSpan={6} className="text-center py-6">
                                                No support requests found
                                            </TableCell>
                                        </TableRow>
                                    )}
                                </TableBody>
                            </Table>
                        </div>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
