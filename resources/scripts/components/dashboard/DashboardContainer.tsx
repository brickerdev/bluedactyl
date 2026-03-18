import { Bars, ChevronDown, House, LayoutCellsLarge, SlidersVertical } from '@gravity-ui/icons';
import { useStoreState } from 'easy-peasy';
import { useEffect, useState } from 'react';
import { useLocation } from 'react-router-dom';
import useSWR from 'swr';

import ServerRow from '@/components/dashboard/ServerRow';
import Pagination from '@/components/elements/Pagination';
import { PageListContainer } from '@/components/elements/pages/PageList';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Separator } from '@/components/ui/separator';
import { Skeleton } from '@/components/ui/skeleton';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

import getServers from '@/api/getServers';
import { PaginatedResult } from '@/api/http';
import { Server } from '@/api/server/getServer';

import useFlash from '@/plugins/useFlash';
import { usePersistedState } from '@/plugins/usePersistedState';

const DashboardContainer = () => {
    const getTitle = () => {
        if (serverViewMode === 'admin-all') return 'All Servers (Admin)';
        if (serverViewMode === 'all') return 'All Accessible Servers';
        return 'Your Servers';
    };

    const { search } = useLocation();
    const defaultPage = Number(new URLSearchParams(search).get('page') || '1');

    const [page, setPage] = useState(!isNaN(defaultPage) && defaultPage > 0 ? defaultPage : 1);
    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const uuid = useStoreState((state) => state.user.data!.uuid);
    const rootAdmin = useStoreState((state) => state.user.data!.rootAdmin);

    const [serverViewMode, setServerViewMode] = usePersistedState<'owner' | 'admin-all' | 'all'>(
        `${uuid}:server_view_mode`,
        'owner',
    );

    const [dashboardDisplayOption, setDashboardDisplayOption] = usePersistedState(
        `${uuid}:dashboard_display_option`,
        'list',
    );

    const getApiType = (): string | undefined => {
        if (serverViewMode === 'owner') return 'owner';
        if (serverViewMode === 'admin-all') return 'admin-all';
        if (serverViewMode === 'all') return 'all';
        return undefined;
    };

    const {
        data: servers,
        error,
        isLoading,
    } = useSWR<PaginatedResult<Server>>(
        ['/api/client/servers', serverViewMode, page],
        () => getServers({ page, type: getApiType() }),
        { revalidateOnFocus: false },
    );

    useEffect(() => {
        if (!servers) return;
        if (servers.pagination.currentPage > 1 && !servers.items.length) {
            setPage(1);
        }
    }, [servers?.pagination.currentPage]);

    useEffect(() => {
        window.history.replaceState(null, document.title, `/${page <= 1 ? '' : `?page=${page}`}`);
    }, [page]);

    useEffect(() => {
        if (error) clearAndAddHttpError({ key: 'dashboard', error });
        if (!error) clearFlashes('dashboard');
    }, [error]);

    const LoadingSkeleton = () => (
        <div className='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6'>
            {[1, 2, 3, 4, 5, 6].map((i) => (
                <Card key={i}>
                    <CardContent className='p-6'>
                        <div className='flex items-center space-x-4'>
                            <Skeleton className='h-12 w-12 rounded-full' />
                            <div className='space-y-2 flex-1'>
                                <Skeleton className='h-4 w-3/4' />
                                <Skeleton className='h-4 w-1/2' />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            ))}
        </div>
    );

    const EmptyState = () => (
        <div className='text-center py-12 border rounded-lg bg-muted/50'>
            <div className='w-16 h-16 rounded-full bg-muted flex items-center justify-center mx-auto mb-4'>
                <House className='h-8 w-8 text-muted-foreground' />
            </div>
            <h3 className='text-lg font-semibold mb-2'>
                {serverViewMode === 'admin-all' ? 'No other servers found' : 'No servers found'}
            </h3>
            <p className='text-sm text-muted-foreground max-w-sm mx-auto'>
                {serverViewMode === 'admin-all'
                    ? 'There are no other servers to display.'
                    : 'There are no servers associated with your account.'}
            </p>
        </div>
    );

    return (
        <div className='container mx-auto p-6'>
            <Tabs
                defaultValue={dashboardDisplayOption}
                onValueChange={(value) => setDashboardDisplayOption(value)}
                className='w-full'
            >
                <div className='flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4'>
                    <div>
                        <h1 className='text-3xl font-bold tracking-tight'>{getTitle()}</h1>
                        <p className='text-muted-foreground'>Manage and monitor your server instances.</p>
                    </div>
                    <div className='flex items-center gap-4'>
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant='outline' className='gap-2'>
                                    <SlidersVertical className='h-4 w-4' />
                                    <span>{getTitle()}</span>
                                    <ChevronDown className='h-3 w-3' />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align='end' className='z-[9999]'>
                                <DropdownMenuItem onSelect={() => setServerViewMode('owner')}>
                                    Your Servers Only
                                </DropdownMenuItem>
                                {rootAdmin && (
                                    <DropdownMenuItem onSelect={() => setServerViewMode('admin-all')}>
                                        All Servers (Admin)
                                    </DropdownMenuItem>
                                )}
                                <DropdownMenuItem onSelect={() => setServerViewMode('all')}>
                                    All Servers
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>

                        <Separator orientation='vertical' className='h-8 hidden md:block' />

                        <TabsList className='bg-muted/50'>
                            <TabsTrigger value='list'>
                                <Bars className='h-4 w-4' />
                            </TabsTrigger>
                            <TabsTrigger value='grid'>
                                <LayoutCellsLarge className='h-4 w-4' />
                            </TabsTrigger>
                        </TabsList>
                    </div>
                </div>

                {isLoading ? (
                    <LoadingSkeleton />
                ) : !servers || servers.items.length === 0 ? (
                    <EmptyState />
                ) : (
                    <>
                        <TabsContent value='list' className='mt-0'>
                            <Pagination data={servers} onPageSelect={setPage}>
                                {({ items }) => (
                                    <PageListContainer>
                                        <div className='space-y-3'>
                                            {items.map((server) => (
                                                <ServerRow key={server.uuid} className='flex-row' server={server} />
                                            ))}
                                        </div>
                                    </PageListContainer>
                                )}
                            </Pagination>
                        </TabsContent>

                        <TabsContent value='grid' className='mt-0'>
                            <Pagination data={servers} onPageSelect={setPage}>
                                {({ items }) => (
                                    <div className='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6'>
                                        {items.map((server) => (
                                            <ServerRow
                                                key={server.uuid}
                                                className='items-start! flex-col w-full gap-4 [&>div~div]:w-full'
                                                server={server}
                                            />
                                        ))}
                                    </div>
                                )}
                            </Pagination>
                        </TabsContent>
                    </>
                )}
            </Tabs>
        </div>
    );
};

export default DashboardContainer;
