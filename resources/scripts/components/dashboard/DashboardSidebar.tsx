import { File, Settings2, LayoutDashboard, GaugeCircle, ListTodo, Container, Boxes, Accessibility, MoreVertical, Home, Key, KeyRound, Settings } from 'lucide-react';
import { NavLink } from 'react-router-dom';

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/elements/DropdownMenu';
import Logo from '@/components/elements/PyroLogo';
import { cn } from '@/lib/utils';

interface DashboardSidebarProps {
    rootAdmin?: boolean;
    onTriggerLogout: () => void;
    onSelectAdminPanel: () => void;
}

export default function DashboardSidebar({
    rootAdmin,
    onTriggerLogout,
    onSelectAdminPanel,
}: DashboardSidebarProps) {
    return (
        <>
            {/* Desktop: 按 brickerium Sidebar 结构 — Sidebar > Header > Menu > Content > Rail */}
            <aside
                className={cn(
                    'relative hidden lg:flex lg:flex-col lg:shrink-0 lg:w-[280px] lg:mr-2 lg:rounded-lg lg:overflow-x-hidden lg:p-6'
                )}
                data-slot='sidebar'
            >
                <header data-slot='sidebar-header' className='shrink-0'>
                    <ul className='flex list-none p-0 m-0' data-slot='sidebar-menu' aria-label='Sidebar menu'>
                        <li className='flex flex-row flex-1 min-w-0' data-slot='sidebar-menu-item'>
                            <NavLink
                                to='/'
                                className='flex items-center gap-2 shrink-0 h-8 w-fit p-1.5'
                                data-slot='sidebar-menu-button'
                            >
                                <Logo uniqueId='dashboard-sidebar-desktop' />
                                <span className='text-base font-semibold'>Panel</span>
                            </NavLink>
                            <div className='ml-auto flex items-center' data-slot='sidebar-header-actions'>
                                <DropdownMenu>
                                    <DropdownMenuTrigger asChild>
                                        <button
                                            type='button'
                                            className='w-10 h-10 flex items-center justify-center rounded-md p-2 cursor-pointer'
                                            aria-label='Open menu'
                                        >
                                            <MoreVertical size={22} />
                                        </button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent className='z-[99999]' sideOffset={8}>
                                        {rootAdmin && (
                                            <DropdownMenuItem onSelect={onSelectAdminPanel}>
                                                Admin Panel
                                                <span className='ml-2 z-10 rounded-full px-2 py-1 text-xs'>Staff</span>
                                            </DropdownMenuItem>
                                        )}
                                        <DropdownMenuSeparator />
                                        <DropdownMenuItem onSelect={onTriggerLogout}>Log Out</DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </div>
                        </li>
                    </ul>
                </header>
                <div className='flex flex-1 flex-col min-h-0 mt-6' data-slot='sidebar-content'>
                    <div data-slot='sidebar-group'>
                        <div className='flex flex-col gap-1 text-sm' data-slot='sidebar-group-content'>
                            <ul className='flex flex-col gap-0 list-none p-0 m-0' data-slot='sidebar-menu' aria-label='Nav main'>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink
                                        to='/dashboard'
                                        end
                                        className={({ isActive }) => cn('flex flex-row items-center gap-2 py-3 min-h-[48px] font-medium rounded-lg transition-colors duration-200 select-none [&_svg]:fill-current', isActive && 'sidebar-link-active')}
                                        title='仪表板'
                                        data-slot='sidebar-menu-button'
                                    >
                                        <LayoutDashboard size={22} className='shrink-0' />
                                        <span>仪表板</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink
                                        to='/monitor'
                                        end
                                        className={({ isActive }) => cn('flex flex-row items-center gap-2 py-3 min-h-[48px] font-medium rounded-lg transition-colors duration-200 select-none [&_svg]:fill-current', isActive && 'sidebar-link-active')}
                                        title='监控'
                                        data-slot='sidebar-menu-button'
                                    >
                                        <GaugeCircle size={22} className='shrink-0' />
                                        <span>监控</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink
                                        to='/assistant'
                                        end
                                        className={({ isActive }) => cn('flex flex-row items-center gap-2 py-3 min-h-[48px] font-medium rounded-lg transition-colors duration-200 select-none [&_svg]:fill-current', isActive && 'sidebar-link-active')}
                                        title='指令助手'
                                        data-slot='sidebar-menu-button'
                                    >
                                        <Accessibility size={22} className='shrink-0' />
                                        <span>指令助手</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink
                                        to='/files'
                                        end
                                        className={({ isActive }) => cn('flex flex-row items-center gap-2 py-3 min-h-[48px] font-medium rounded-lg transition-colors duration-200 select-none [&_svg]:fill-current', isActive && 'sidebar-link-active')}
                                        title='文件管理'
                                        data-slot='sidebar-menu-button'
                                    >
                                        <File size={22} className='shrink-0' />
                                        <span>文件管理</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink
                                        to='/tasks'
                                        end
                                        className={({ isActive }) => cn('flex flex-row items-center gap-2 py-3 min-h-[48px] font-medium rounded-lg transition-colors duration-200 select-none [&_svg]:fill-current', isActive && 'sidebar-link-active')}
                                        title='计划任务'
                                        data-slot='sidebar-menu-button'
                                    >
                                        <ListTodo size={22} className='shrink-0' />
                                        <span>计划任务</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink
                                        to='/images'
                                        end
                                        className={({ isActive }) => cn('flex flex-row items-center gap-2 py-3 min-h-[48px] font-medium rounded-lg transition-colors duration-200 select-none [&_svg]:fill-current', isActive && 'sidebar-link-active')}
                                        title='镜像'
                                        data-slot='sidebar-menu-button'
                                    >
                                        <Container size={22} className='shrink-0' />
                                        <span>镜像</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink
                                        to='/presets'
                                        end
                                        className={({ isActive }) => cn('flex flex-row items-center gap-2 py-3 min-h-[48px] font-medium rounded-lg transition-colors duration-200 select-none [&_svg]:fill-current', isActive && 'sidebar-link-active')}
                                        title='预设'
                                        data-slot='sidebar-menu-button'
                                    >
                                        <Boxes size={22} className='shrink-0' />
                                        <span>预设</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink
                                        to='/settings'
                                        end
                                        className={({ isActive }) => cn('flex flex-row items-center gap-2 py-3 min-h-[48px] font-medium rounded-lg transition-colors duration-200 select-none [&_svg]:fill-current', isActive && 'sidebar-link-active')}
                                        title='设置'
                                        data-slot='sidebar-menu-button'
                                    >
                                        <Settings2 size={22} className='shrink-0' />
                                        <span>设置</span>
                                    </NavLink>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div data-slot='sidebar-rail' className='absolute right-0 top-0 bottom-0 w-px' aria-hidden />
            </aside>

            {/* Mobile: 同结构 — Header + Content(Mobile) + 右侧菜单 */}
            <header
                className='lg:hidden fixed top-0 left-0 right-0 z-50 flex items-center justify-between h-14 px-4'
                data-slot='sidebar'
            >
                <div data-slot='sidebar-header' className='flex items-center gap-2 flex-1 min-w-0'>
                    <NavLink
                        to='/'
                        className='flex items-center gap-2 shrink-0 h-8 w-fit p-1.5'
                        data-slot='sidebar-menu-button'
                    >
                        <Logo uniqueId='dashboard-sidebar-mobile' />
                        <span className='text-base font-semibold'>Panel</span>
                    </NavLink>
                </div>
                <div className='flex items-center gap-1' data-slot='sidebar-content'>
                    <div data-slot='sidebar-group' className='overflow-x-auto max-w-[50vw] scrollbar-none'>
                        <div className='flex items-center gap-0.5' data-slot='sidebar-group-content'>
                            <ul className='flex items-center gap-0.5 list-none p-0 m-0' data-slot='sidebar-menu' aria-label='Nav main'>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink to='/dashboard' end className={() => cn('flex items-center gap-1.5 rounded-md px-2.5 py-2 text-xs font-medium whitespace-nowrap transition-colors duration-200')} title='仪表板' data-slot='sidebar-menu-button'>
                                        <LayoutDashboard size={18} className='shrink-0' />
                                        <span>仪表板</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink to='/monitor' end className={() => cn('flex items-center gap-1.5 rounded-md px-2.5 py-2 text-xs font-medium whitespace-nowrap transition-colors duration-200')} title='监控' data-slot='sidebar-menu-button'>
                                        <GaugeCircle size={18} className='shrink-0' />
                                        <span>监控</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink to='/assistant' end className={() => cn('flex items-center gap-1.5 rounded-md px-2.5 py-2 text-xs font-medium whitespace-nowrap transition-colors duration-200')} title='指令助手' data-slot='sidebar-menu-button'>
                                        <Accessibility size={18} className='shrink-0' />
                                        <span>指令助手</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink to='/files' end className={() => cn('flex items-center gap-1.5 rounded-md px-2.5 py-2 text-xs font-medium whitespace-nowrap transition-colors duration-200')} title='文件管理' data-slot='sidebar-menu-button'>
                                        <File size={18} className='shrink-0' />
                                        <span>文件管理</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink to='/tasks' end className={() => cn('flex items-center gap-1.5 rounded-md px-2.5 py-2 text-xs font-medium whitespace-nowrap transition-colors duration-200')} title='计划任务' data-slot='sidebar-menu-button'>
                                        <ListTodo size={18} className='shrink-0' />
                                        <span>计划任务</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink to='/images' end className={() => cn('flex items-center gap-1.5 rounded-md px-2.5 py-2 text-xs font-medium whitespace-nowrap transition-colors duration-200')} title='镜像' data-slot='sidebar-menu-button'>
                                        <Container size={18} className='shrink-0' />
                                        <span>镜像</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink to='/presets' end className={() => cn('flex items-center gap-1.5 rounded-md px-2.5 py-2 text-xs font-medium whitespace-nowrap transition-colors duration-200')} title='预设' data-slot='sidebar-menu-button'>
                                        <Boxes size={18} className='shrink-0' />
                                        <span>预设</span>
                                    </NavLink>
                                </li>
                                <li data-slot='sidebar-menu-item'>
                                    <NavLink to='/settings' end className={() => cn('flex items-center gap-1.5 rounded-md px-2.5 py-2 text-xs font-medium whitespace-nowrap transition-colors duration-200')} title='设置' data-slot='sidebar-menu-button'>
                                        <Settings2 size={18} className='shrink-0' />
                                        <span>设置</span>
                                    </NavLink>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div className='ml-auto flex items-center' data-slot='sidebar-header-actions'>
                    <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                            <button
                                type='button'
                                className='w-9 h-9 flex items-center justify-center rounded-md p-2'
                                aria-label='Open menu'
                            >
                                <MoreVertical size={22} />
                            </button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent className='z-[99999]' sideOffset={8}>
                            {rootAdmin && (
                                <DropdownMenuItem onSelect={onSelectAdminPanel}>
                                    Admin Panel
                                    <span className='ml-2 z-10 rounded-full px-2 py-1 text-xs'>Staff</span>
                                </DropdownMenuItem>
                            )}
                            <DropdownMenuSeparator />
                            <DropdownMenuItem onSelect={onTriggerLogout}>Log Out</DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </header>
        </>
    );
}
