import { useState } from 'react';
import { useForm } from '@inertiajs/react';
import { Card, CardContent, CardFooter } from "@/components/ui/card";
import { Textarea } from "@/components/ui/textarea";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Button } from "@/components/ui/button";
import {
    CrownIcon,
    SaveIcon,
    UserCogIcon,
    CheckIcon,
    XCircleIcon,
    AlertTriangleIcon,
    CalculatorIcon
} from "lucide-react";
import { type SupportRequest } from '@/types';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,

} from "@/components/ui/dialog";

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
}

interface StaffManagementConsoleProps {
    supportRequest: ViewSupportRequest;
    availableStaffMembers: StaffMember[];
    currentUserId: number;
}

// Define the form data interface to match the expected structure
interface FormData {
    title: string;
    status: 'open' | 'in_progress' | 'closed';
    assigned_staff_user_id: string;
    staff_notes: string;
    [key: string]: string | boolean | number | null | undefined;
}

export default function StaffManagementConsole({
                                                   supportRequest,
                                                   availableStaffMembers,
                                                   currentUserId
                                               }: StaffManagementConsoleProps) {
    const [isCloseWithoutChargeDialogOpen, setIsCloseWithoutChargeDialogOpen] = useState(false);
    const [isCloseAndCalculateDialogOpen, setIsCloseAndCalculateDialogOpen] = useState(false);

    // Properly type the form data
    const { data, setData, post, processing, errors } = useForm<FormData>({
        title: supportRequest.title,
        status: supportRequest.status,
        assigned_staff_user_id: supportRequest.assigned_staff_user_id?.toString() ?? 'unassigned',
        staff_notes: supportRequest.staff_notes || '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // Update the form data before submitting
        if (data.assigned_staff_user_id === 'unassigned') {
            setData('assigned_staff_user_id', null as unknown as string);
        } else {
            // Keep as string in the form data
            setData('assigned_staff_user_id', data.assigned_staff_user_id);
        }

        // Submit the form with the data already in the useForm hook
        post(route('support.update', { supportRequest: supportRequest.id }));
    };

    const handleAssignToMe = () => {
        setData('assigned_staff_user_id', currentUserId.toString());
    };

    const handleCloseWithoutCharge = () => {
        setData('status', 'closed');
        post(route('support.update', {
            supportRequest: supportRequest.id,
            close_without_charge: true,
            calculated_cost: 0
        }));
        setIsCloseWithoutChargeDialogOpen(false);
    };

    const handleCloseAndCalculate = () => {
        setData('status', 'closed');
        post(route('support.update', {
            supportRequest: supportRequest.id,
            calculate_charge: true
        }));
        setIsCloseAndCalculateDialogOpen(false);
    };

    return (
        <>
            <Card className="border border-amber-200 dark:border-amber-800">
                <form onSubmit={handleSubmit}>
                    <CardContent className="p-6 space-y-6">
                        <div className="flex items-center gap-2 mb-4">
                            <div className="bg-amber-100 dark:bg-amber-950 p-2 rounded-full">
                                <CrownIcon size={18} className="text-amber-600 dark:text-amber-400" />
                            </div>
                            <h2 className="text-lg font-semibold">Staff Management Console</h2>
                        </div>

                        <div>
                            <label className="text-sm font-medium text-muted-foreground block mb-2">Title</label>
                            <Textarea
                                value={data.title}
                                onChange={e => setData('title', e.target.value)}
                                className="w-full min-h-[60px]"
                                placeholder="Update the request title..."
                            />
                            {errors.title && <p className="text-sm text-red-500 mt-1">{errors.title}</p>}
                        </div>

                        <div>
                            <label className="text-sm font-medium text-muted-foreground block mb-2">Status</label>
                            <Select
                                value={data.status}
                                onValueChange={(value: 'open' | 'in_progress' | 'closed') => setData('status', value)}
                            >
                                <SelectTrigger className="w-full">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="open">Open</SelectItem>
                                    <SelectItem value="in_progress">In Progress</SelectItem>
                                </SelectContent>
                            </Select>
                            {errors.status && <p className="text-sm text-red-500 mt-1">{errors.status}</p>}
                        </div>

                        <div>
                            <label className="text-sm font-medium text-muted-foreground block mb-2">Assigned Staff Member</label>
                            <Select
                                value={data.assigned_staff_user_id}
                                onValueChange={value => setData('assigned_staff_user_id', value)}
                            >
                                <SelectTrigger className="w-full">
                                    <SelectValue placeholder="Unassigned" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="unassigned">Unassigned</SelectItem>
                                    {availableStaffMembers.map((staff) => (
                                        <SelectItem key={staff.id} value={staff.id.toString()}>
                                            <div className="flex items-center gap-2">
                                                {staff.avatar && (
                                                    <img
                                                        src={staff.avatar}
                                                        alt={staff.name}
                                                        className="w-5 h-5 rounded-full"
                                                    />
                                                )}
                                                <span>{staff.name}</span>
                                            </div>
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            {errors.assigned_staff_user_id && (
                                <p className="text-sm text-red-500 mt-1">{errors.assigned_staff_user_id}</p>
                            )}
                        </div>

                        <div className="flex justify-end mt-2">
                            <Button
                                type="button"
                                variant="secondary"
                                size="sm"
                                className="flex items-center gap-2"
                                disabled={data.assigned_staff_user_id === currentUserId.toString()}
                                onClick={handleAssignToMe}
                            >
                                <UserCogIcon size={14} />
                                <span>Assign to Me</span>
                            </Button>
                        </div>

                        <div>
                            <label className="text-sm font-medium text-muted-foreground block mb-2">Staff Notes</label>
                            <Textarea
                                value={data.staff_notes}
                                onChange={e => setData('staff_notes', e.target.value)}
                                className="w-full min-h-[100px]"
                                placeholder="Add internal notes..."
                            />
                            {errors.staff_notes && <p className="text-sm text-red-500 mt-1">{errors.staff_notes}</p>}
                        </div>

                        <div className="mt-6 p-4 bg-red-100 border border-red-300 rounded-md">
                            <div className="text-sm text-red-700 font-semibold mb-2">
                                <strong>Warning:</strong> Closing this ticket will not incur any costs for the requestee.
                            </div>
                            <div className="text-sm mb-4 text-red-800">
                                Please be certain before proceeding. This action will permanently close the ticket without charging {(supportRequest.submitter as {name: string}).name}.
                            </div>
                            <div className="flex justify-end">
                                <Button
                                    type="button"
                                    variant="destructive"
                                    size="sm"
                                    className="w-[180px] flex items-center gap-2"
                                    onClick={() => setIsCloseWithoutChargeDialogOpen(true)}
                                >
                                    <XCircleIcon size={14} />
                                    <span>Close Without Charge</span>
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter className="bg-muted/30 border-t p-4 flex justify-between gap-2">
                        <Button
                            type="submit"
                            size="sm"
                            className="flex items-center gap-2"
                            disabled={processing}
                        >
                            <SaveIcon size={14} />
                            <span>Save Details</span>
                        </Button>
                        <Button
                            type="button"
                            variant="positive"
                            size="sm"
                            className="flex items-center gap-2 ml-auto"
                            onClick={() => setIsCloseAndCalculateDialogOpen(true)}
                        >
                            <CheckIcon size={14} />
                            <span>Close & Calculate</span>
                        </Button>
                    </CardFooter>
                </form>
            </Card>

            {/* Close Without Charge Confirmation Dialog */}
            <Dialog open={isCloseWithoutChargeDialogOpen} onOpenChange={setIsCloseWithoutChargeDialogOpen}>
                <DialogContent className="sm:max-w-md">
                    <DialogHeader>
                        <DialogTitle className="flex items-center gap-2 text-red-600">
                            <XCircleIcon size={18} />
                            Close Without Charge
                        </DialogTitle>
                        <DialogDescription>
                            This will close the support ticket without charging the customer.
                        </DialogDescription>
                    </DialogHeader>
                    <div className="bg-red-50 p-4 rounded-md border border-red-200">
                        <div className="flex gap-3">
                            <AlertTriangleIcon className="text-red-500 h-5 w-5 flex-shrink-0 mt-0.5" />
                            <div>
                                <h4 className="font-medium text-red-800 mb-1">Warning</h4>
                                <p className="text-sm text-red-700">
                                    You are about to close ticket #{supportRequest.id} for {supportRequest.submitter.name} without charging them.
                                    This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                    <DialogFooter className="sm:justify-between">
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => setIsCloseWithoutChargeDialogOpen(false)}
                        >
                            Cancel
                        </Button>
                        <Button
                            type="button"
                            variant="destructive"
                            onClick={handleCloseWithoutCharge}
                        >
                            Yes, Close Without Charge
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            {/* Close and Calculate Confirmation Dialog */}
            <Dialog open={isCloseAndCalculateDialogOpen} onOpenChange={setIsCloseAndCalculateDialogOpen}>
                <DialogContent className="sm:max-w-md">
                    <DialogHeader>
                        <DialogTitle className="flex items-center gap-2 text-green-600">
                            <CalculatorIcon size={18} />
                            Close & Calculate Charges
                        </DialogTitle>
                        <DialogDescription>
                            This will close the ticket and calculate charges based on time spent.
                        </DialogDescription>
                    </DialogHeader>
                    <div className="bg-green-50 p-4 rounded-md border border-green-200">
                        <div className="flex gap-3">
                            <CheckIcon className="text-green-500 h-5 w-5 flex-shrink-0 mt-0.5" />
                            <div>
                                <h4 className="font-medium text-green-800 mb-1">Confirmation</h4>
                                <p className="text-sm text-green-700">
                                    You are closing ticket #{supportRequest.id} for {supportRequest.submitter.name}.
                                    Our system will calculate charges based on the time spent and resources used.
                                </p>
                            </div>
                        </div>
                    </div>
                    <DialogFooter className="sm:justify-between">
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => setIsCloseAndCalculateDialogOpen(false)}
                        >
                            Cancel
                        </Button>
                        <Button
                            type="button"
                            variant="default"
                            className="bg-green-600 hover:bg-green-700"
                            onClick={handleCloseAndCalculate}
                        >
                            Confirm and Calculate
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </>
    );
}
